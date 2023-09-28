<?php

namespace BWC\MembersLocalTime\XF\Entity;

class User extends XFCP_User
{
    /**
     * @return int
     */
    public function getLocalTime()
    {
        $visitor = \XF::visitor();

        try {
            $timezone = new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            $timezone = \XF::language()->getTimeZone();
        }

        try {
            $visitorTz = new \DateTimeZone($visitor->timezone);
        } catch (\Exception $e) {
            $visitorTz = \XF::language()->getTimeZone();
        }

        $date = new \DateTime(null, $timezone);
        $visitorDate = new \DateTime(null, $visitorTz);
        return $date->getTimestamp() + $date->getOffset() - $visitorDate->getOffset();
    }
}