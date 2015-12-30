<?php
use Symfony\Component\HttpFoundation\Request;

class Lk_controller extends Controller
{
    use sorterTrait;

    public function show($filter = 'all')
    {
        Auth::isLoggedOrDie(App::$instance);
        $selectedFilter = mb_strtolower($filter);
        $filters = ['all', 'meaning', 'pending', 'status','invited', 'deleted'];
        if (!in_array($selectedFilter, $filters))
            App::$instance->MQ->setMessage("Фильтр не определен, вам будут показаны все записи");
        $statusesForSelect = [];
        switch ($selectedFilter) {
            case('all'):
                $statusesForSelect = [VKS_STATUS_DELETED, VKS_STATUS_APPROVED, VKS_STATUS_PENDING, VKS_STATUS_DROP_BY_USER];
                break;
            case('meaning'):
                $statusesForSelect = [VKS_STATUS_APPROVED, VKS_STATUS_PENDING];
                break;
            case('pending'):
                $statusesForSelect = [VKS_STATUS_PENDING];
                break;
            case('status'):
                $statusesForSelect = [VKS_STATUS_APPROVED];
                break;
            case('deleted'):
                $statusesForSelect = [VKS_STATUS_DELETED, VKS_STATUS_DROP_BY_USER];
                break;
            default:
                $statusesForSelect = [VKS_STATUS_DELETED, VKS_STATUS_APPROVED, VKS_STATUS_PENDING, VKS_STATUS_DROP_BY_USER];
                break;
        }
        $vksList = Vks::where('owner_id', App::$instance->user->id)
            ->whereIn('status', $statusesForSelect)
            ->full()
            ->take($this->getQlimit(30))
            ->skip($this->getQOffset())
            ->orderBy($this->getQOrder(), $this->getQVector())
            ->get();
        $vksCtrl = new Vks_controller();
        foreach ($vksList as &$vks) {
            $vks->manipulatable = VKSTimeAnalizator::isManipulatable($vks);
            $vks->dayBeforeStart = VKSTimeAnalizator::is24ForBegin($vks);
            $vks = $vksCtrl->humanize($vks);
//            dump($vks);
        }

        $recordsCount = Vks::where('owner_id', App::$instance->user->id)->whereIn('status', $statusesForSelect)->count();
        //pages
        $pages = RenderEngine::makePagination($recordsCount, $this->getQlimit(30), 'route');

        $this->render("lk/index", compact('vksList', 'pages', 'filter'));
    }

}