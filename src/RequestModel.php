<?php

namespace EasyCloudRequest\Tencent;

use ReflectionObject;
use TencentCloud\Common\AbstractModel;

class RequestModel extends AbstractModel
{
    public function __construct(array $args = [])
    {
        foreach ($args as $field => $value) {
            $this->{$field} = $value;
        }
    }

    public function deserialize($param)
    {
        // nothing
    }

    public function serialize()
    {
        $memberRet = [];
        $ref = new ReflectionObject($this);
        $memberList = $ref->getProperties();
        foreach ($memberList as $x => $member) {
            $name = ucfirst($member->getName());
            $member->setAccessible(true);
            $value = $member->getValue($this);
            if ($value === null) {
                continue;
            }
            if ($value instanceof AbstractModel) {
                $memberRet[$name] = $this->objSerialize($value);
            } elseif (is_array($value)) {
                $memberRet[$name] = $this->arraySerialize($value);
            } else {
                $memberRet[$name] = $value;
            }
        }
        return $memberRet;
    }

    private function objSerialize($obj)
    {
        $memberRet = [];
        $ref = new \ReflectionClass(get_class($obj));
        $memberList = $ref->getProperties();
        foreach ($memberList as $x => $member) {
            $name = ucfirst($member->getName());
            $member->setAccessible(true);
            $value = $member->getValue($obj);
            if ($value === null) {
                continue;
            }
            if ($value instanceof AbstractModel) {
                $memberRet[$name] = $this->objSerialize($value);
            } else if (is_array($value)) {
                $memberRet[$name] = $this->arraySerialize($value);
            } else {
                $memberRet[$name] = $value;
            }
        }
        return $memberRet;
    }

    private function arraySerialize($memberList)
    {
        $memberRet = [];
        foreach ($memberList as $name => $value) {
            if ($value === null) {
                continue;
            }
            if ($value instanceof AbstractModel) {
                $memberRet[$name] = $this->objSerialize($value);
            } elseif (is_array($value)) {
                $memberRet[$name] = $this->arraySerialize($value);
            } else {
                $memberRet[$name] = $value;
            }
        }
        return $memberRet;
    }
}
