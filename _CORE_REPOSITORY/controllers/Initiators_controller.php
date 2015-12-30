<?php

class Initiators_controller extends Controller
{

    public function index()
    {
        Auth::isAdminOrDie(App::$instance);
        $initiators = Initiator::all();
        $this->render('Initiators/index',compact('initiators'));
    }
    public function create()
    {
        Auth::isAdminOrDie(App::$instance);
        $this->render('Initiators/create');
    }
    public function store()
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();
        $request = $this->request->request;
        $this->validator->validate([
            'Название' => [$request->get('name'), 'required|max(255)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $ititiator = new Initiator();
        $ititiator->fill($request->all());
        $ititiator->save();

        App::$instance->MQ->setMessage("Успешно создано");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Добавлен инициатор'.$ititiator->name);
        ST::redirectToRoute('Initiators/index');

    }
    public function edit($id)
    {
        Auth::isAdminOrDie(App::$instance);
        $this->isDefaultUserIteractBlock($id);
        $initiator = Initiator::findorFail($id);
        $this->render('Initiators/edit',compact('initiator'));

    }
    public function update($id)
    {

        $this->isDefaultUserIteractBlock($id);
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();
        $request = $this->request->request;
        $this->validator->validate([
            'Название' => [$request->get('name'), 'required|max(255)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $nitiator = Initiator::findOrFail($id);
        $nitiator->fill($request->all());
        $nitiator->save();
        App::$instance->MQ->setMessage("Успешно отредактировано");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Инициатор отредактирован'.$nitiator->name);
        ST::redirectToRoute('Initiators/index');
    }
    public function delete($id)
    {
        $this->isDefaultUserIteractBlock($id);
        Auth::isAdminOrDie(App::$instance);
        $initiator = Initiator::findorFail($id);
        $initiator->delete();
        App::$instance->MQ->setMessage("Успешно удалено");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Инициатор удален'.$initiator->name);
        ST::redirectToRoute('Initiators/index');

    }

    private function isDefaultUserIteractBlock($id)
    {
        Auth::isAdminOrDie(App::$instance);
        if (in_array($id, [1])) {
            App::$instance->MQ->setMessage('Нелья редактировать системныe записи');
            ST::redirect("back");
        }
    }
}