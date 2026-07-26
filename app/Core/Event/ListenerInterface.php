<?php
/**
 * Listener Interface - رابط شنونده‌های رویداد
 */

namespace OmniCMS\Core\Event;

interface ListenerInterface
{
    /**
     * Handle the event
     * 
     * @param Event $event
     * @return mixed
     */
    public function handle(Event $event);
}
