<?php
//vks_ca_negotiator
App::$instance->registerService("vks_ca_negotiator", "CaVksNegotiator");
//vks mail report builder
App::$instance->registerService("vks_report_builder", "VksMailReportBuilder");
//standart controller
App::$instance->registerService("standart_controller", "Controller");