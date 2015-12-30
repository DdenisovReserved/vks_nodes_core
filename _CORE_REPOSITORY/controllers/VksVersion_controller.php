<?php

class VksVersion_controller extends Controller
{

    public function create(Vks $vksObj)
    {
        $vks = Vks::full()->find($vksObj->id);

        $version = new VksVersion();
        $version->vks_id = $vks->id;
        $version->version = $this->findLastVersion($vks->id) + 1;
        $version->dump = serialize($vks->toArray());
        $version->changed_by = App::$instance->user->id;

        try {
            $version->save();
        } catch (Exception $e) {
            return false;
        }
        return $version->version;
    }

    public function getVersionsList($vksId)
    {
        return VksVersion::where("vks_id", $vksId)->with("changer")->orderby("version", 'desc')->take(25)->get(['changed_by', 'version', 'created_at']);
    }


    public function compare($vksId, $originVerId, $copyVerId)
    {
//        $this->error("500");
        $origVks = $this->pullVersion($vksId, $originVerId);
        $comparedVks = $this->pullVersion($vksId, $copyVerId);
        $compare_params = array();
        foreach($comparedVks as $key => $comparedVersion) {
            $compare_params[$key] = $comparedVersion == $origVks[$key] ? true : false;
        }
//        dump($compare_params);
        $versions = $this->getVersionsList($vksId);
        $this->render("vks/admin/compare", compact("origVks", "comparedVks", "versions", "compare_params"));

    }

    public function pullVersion($vksId, $versionId)
    {
        if ($versionId == 0) {
            $vks = Vks::full()->findOrFail($vksId);
            $vks->version = new stdClass();
            $vks->version->version = null;
        } else {
            $vksD = unserialize(VksVersion::where("vks_id", $vksId)->where("version", $versionId)->first(['dump'])->dump);
            $vks = new Vks();
            foreach ((array)$vksD as $param => $value) {
                $vks->$param = $value;
            }

            $vks->version = VksVersion::where("vks_id", $vksId)->with('changer')->where("version", $versionId)->first(['vks_id', 'version', 'created_at', 'changed_by'])->toArray();
        }

        $vksCtrl = new Vks_controller();
        $vksCtrl->humanize($vks);
        $res = array();
        foreach ($vks->toArray() as $key => $val) {
            if (!is_object($val))
                $res[$key] = $val;
            else
                $res[$key] = (array)$val;
        }
        return $res;
    }


    public function pullLastVersion($vksId)
    {
        $result = Null;
        $lv = $this->findLastVersion($vksId);
        if ($lv)
            $result = VksVersion::where("vks_id", $vksId)->where('version', $lv)->first();
        return $result;
    }


    public function findLastVersion($vksId)
    {
        $ver = VksVersion::where("vks_id", $vksId)->max('version');
        return !$ver ? 0 : $ver;
    }


}