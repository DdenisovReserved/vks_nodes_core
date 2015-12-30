<?php

class Search_controller extends Controller
{

    public function index()
    {
        $this->render("search/index");
    }


    public function search($phrase) {
        if (ST::isAjaxRequest()) {
            $vksWSC = new Vks_controller();
            $result = [];

            $result['ws'] = Vks::where("id", $phrase)
                ->whereIn('status',[VKS_STATUS_APPROVED, VKS_STATUS_PENDING])
                ->take(1)
                ->get();


            foreach($result['ws'] as $vks) {
                $vksWSC->humanize($vks);
            }


            print json_encode($result);
        }

    }
}