<?php
use Symfony\Component\HttpFoundation\Request;

class AuthNew_controller extends Controller
{

    public function changePwd($userId)
    {
        if (!Auth::compareIds($userId, App::$instance)) $this->error('403');

        $user = User::findOrFail($userId);

        $this->render("users/v2/changePwd", compact('user'));
    }

    public function savePwd($userId)
    {

        if (!Auth::compareIds($userId, App::$instance)) $this->error('403');
        Token::checkToken();
        $request = Request::createFromGlobals();
        $request = $request->request;
//check pass

        $this->validator->validate([
            'Старый пароль' => [$request->get('old_pwd'), 'required'],
            'Новый_пароль' => [$request->get('new_pwd'), 'required|min(5)|max(25)|pwd'],
            'Новый пароль подтверждение' => [$request->get('new_pwd_confirm'), 'required|matches(Новый_пароль)'],

        ]);
        //if no passes
        if (!$this->validator->passes()) {
            App::$instance->MQ->setMessage($this->validator->errors()->all());
            ST::redirect("back");
        }

        $oldPwd = md5($request->get('old_pwd'));

        if (User::where("id", $userId)->where('password', $oldPwd)->count()) {

            $user = User::find($userId);

            $user->password = md5($request->get('new_pwd'));

            try {
                $user->save();
            } catch (Exception $e) {
                App::$instance->MQ->setMessage('Ошибка при сохранении');
            }

            App::$instance->MQ->setMessage('Пароль обновлен');

            ST::redirect("back");
        } else {

            App::$instance->MQ->setMessage('Старый пароль введен не верно');
            ST::redirect("back");
        }

    }

    public function logout()
    {
        global $_TB_IDENTITY;
        if (Auth::isLogged(App::$instance)) {
            setcookie(md5("logged" . $_TB_IDENTITY[App::$instance->user->origin]['serviceName']), false, time() - 3600, '/', Null, 0);
        } else {
            App::$instance->MQ->setMessage('Вы не авторизированы');
        }

        ST::redirectToRoute("Index/index");
    }

}