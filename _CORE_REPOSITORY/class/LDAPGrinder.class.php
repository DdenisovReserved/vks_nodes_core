<?php
class LDAPGrinder
{
public static function getLdapData ($userLogin) {
    //Соединяемся с каталогом
    global $app;
    $ldapconn = ldap_connect($app->ldap->addr);
    //Выставляем опции
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0);
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_get_option($ldapconn,LDAP_OPT_ERROR_STRING,$err);
    //авторизуемся в каталоге
    $ldap_bind = ldap_bind($ldapconn, $app->ldap->uname,$app->ldap->pass);
    //ищем в каталоге
    $reslultsrh = ldap_search($ldapconn, 'dc=ab,dc=SRB,dc=local ',"(samaccountname=$userLogin*)", array("cn","userprincipalname","telephonenumber"));


    $get_Res = ldap_get_entries($ldapconn, $reslultsrh);
    $res = array();
    @$res['cn'] = $get_Res['0']['cn']['0'];
    @$res['userprincipalname'] = $get_Res['0']['userprincipalname']['0'];
    @$res['telephonenumber'] = $get_Res['0']['telephonenumber']['0'];
    ldap_unbind($ldapconn);
    return $res;
} //function end





} //class end