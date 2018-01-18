<?php

/** @var \Icinga\Application\Modules\Module $this */

$section = $this->menuSection(N_('VMware vSphere DB'))
    ->setIcon('cloud')
    ->setUrl('vspheredb/vcenter')
    ->setPriority(70);

$section->add(N_('Datastores'))
    ->setUrl('vspheredb/overview?type=datastore')
    ->setPriority(10);
$section->add(N_('Hosts'))
    ->setUrl('vspheredb/overview?type=host')
    ->setPriority(20);
$section->add(N_('Virtual Machines'))
    ->setUrl('vspheredb/overview?type=vm')
    ->setPriority(30);
$section->add(N_('Anomalies'))
    ->setUrl('vspheredb/anomalies')
    ->setPriority(45);
$section->add(N_('Performance Counter'))
    ->setUrl('vspheredb/configuration/counters')
    ->setPriority(50);
$section->add(N_('Top VMs'))
    ->setUrl('vspheredb/top/vms')
    ->setPriority(50);
