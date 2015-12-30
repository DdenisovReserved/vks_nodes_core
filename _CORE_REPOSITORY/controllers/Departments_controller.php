<?php

class Departments_controller extends Controller
{
    public function index()
    {
        Auth::isAdminOrDie(App::$instance);
        $departments = Department::orderBy('prefix')->get();
        $this->render('departments/index',compact('departments'));
    }
    public function create()
    {
        Auth::isAdminOrDie(App::$instance);
        $this->render('departments/create');
    }
    public function store()
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();
        $request = $this->request->request;
        $this->validator->validate([
            'Префикс' => [$request->get('prefix'), 'required|int'],
            'Название' => [$request->get('name'), 'required|max(255)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $dep = new Department();
        $dep->fill($request->all());
        $dep->save();

        App::$instance->MQ->setMessage("Успешно создано");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Добавлено подразделение'.$dep->name);
        ST::redirectToRoute('Departments/index');

    }
    public function edit($id)
    {
        Auth::isAdminOrDie(App::$instance);
        $department = Department::findorFail($id);
        $this->render('departments/edit',compact('department'));

    }
    public function update($id)
    {
        Auth::isAdminOrDie(App::$instance);
        Token::checkToken();
        $request = $this->request->request;
        $this->validator->validate([
            'Префикс' => [$request->get('prefix'), 'required|int'],
            'Название' => [$request->get('name'), 'required|max(255)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $dep = Department::findOrFail($id);
        $dep->fill($request->all());
        $dep->save();
        App::$instance->MQ->setMessage("Успешно отредактировано");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Подразделение отредактировано'.$dep->name);
        ST::redirectToRoute('Departments/index');
    }
    public function delete($id)
    {
        Auth::isAdminOrDie(App::$instance);
        if ($id == 1) {
            App::$instance->MQ->setMessage("Это удалить нельзя, просто переименуйте как вам нужно");
            ST::redirect('back');
        }
        $department = Department::findorFail($id);
        $department->delete();
        App::$instance->MQ->setMessage("Успешно удалено");
        App::$instance->log->logWrite(LOG_CONFIG_CHANGE, 'Подразделение удалено'.$department->name);
        ST::redirectToRoute('Departments/index');


    }

}