<?php

namespace Icinga\Module\Vspheredb\Polling\SyncStore;

use Icinga\Module\Vspheredb\RemoteSync\SyncHelper;
use Icinga\Module\Vspheredb\RemoteSync\SyncStats;

abstract class HostPropertyInstancesSyncStore extends SyncStore
{
    use SyncHelper;

    protected $baseKey = 'undefined.property';
    protected $keyProperty = 'undefinedKeyProperty';
    protected $instanceClass = 'undefinedInstanceClass';

    public function store($result, $class, SyncStats $stats)
    {
        $connection = $this->vCenter->getConnection();
        $dbObjects = $class::loadAllForVCenter($this->vCenter);

        $baseKey = $this->baseKey;
        $keyProperty = $this->keyProperty;
        $instanceClass = $this->instanceClass;

        $apiObjects = [];
        foreach ($result as $object) {
            $object = (object) $object;
            $uuid = $this->vCenter->makeBinaryGlobalUuid($object->obj);
            if (! isset($object->$baseKey) || ! property_exists($object->$baseKey, $instanceClass)) {
                // No instance information for this host
                continue;
            }
            foreach ($object->$baseKey->$instanceClass as $instance) {
                $key = $instance->$keyProperty;
                $idx = "$uuid$key";
                $apiObjects[$idx] = $idx;
                if (! array_key_exists($idx, $dbObjects)) {
                    $dbObjects[$idx] = $class::create([
                        'host_uuid'  => $uuid,
                        $keyProperty => $key
                    ], $connection);
                }
                $dbObjects[$idx]->setMapped($instance, $this->vCenter);
            }
        }

        $this->storeSyncObjects($this->db, $dbObjects, $apiObjects, $stats);
    }
}