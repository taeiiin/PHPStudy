<?php
namespace Test;

class GuestMgr extends DataMgr
{
    public function loadGuests(int $order = 0): array
    {
        $guests = $this->loadContents(Guestbook::class);
        return $order === 0 ? array_reverse($guests) : $guests;
    }

    public function saveGuests(Guestbook $guest): void
    {
        $guests = $this->loadGuests(1);
        $guests[] = $guest;
        $this->saveContents($guests);
    }
}