<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Zcwilt 2020 Jul 01 New in v1.5.8-alpha $
 */

namespace Zencart\Events;

use Zencart\Traits\Singleton;

class EventDto
{
    use Singleton;

    /** All registered hashes. */
    private $hashes = [];
    /** Array of eventIDs to arrays of observers. */
    private $observers = [];

    public function getObservers()
    {
        return $this->observers;
    }

    public function setObserver($eventHash, $eventID, $observer)
    {
        if (array_key_exists($eventHash, $this->hashes)) {
            return;
        }
        $this->hashes[] = $eventHash;
        if (!array_key_exists($eventID, $this->observers)) {
            $this->observers[$eventID] = [];
        }
        $this->observers[$eventID][] = &$observer;
    }

    public function removeObserver($eventHash, $eventID, $observer)
    {
        if (!array_key_exists($eventHash, $this->hashes)) {
            return;
        }
        unset($this->hashes[$eventHash]);
        if (($key = array_search($observer, $this->observers[$eventID])) !== false) {
            unset($this->observers[$eventID][$key]);
        }
    }
}
