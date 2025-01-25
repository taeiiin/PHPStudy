<?php
namespace Test;

class GuestMgr extends DataMgr
{
    public function __construct()
    {
        parent::__construct('Guest');
    }

    public function loadGuests(int $orderBy = self::DESC): array
    {
        return $this->loadContents(Guestbook::class, $orderBy);
    }

    public function saveGuests(Guestbook $guest): void
    {
        $this->saveContents($guest->jsonSerialize());
    }

    function map($item): Guestbook
    {
        if (!method_exists(Guestbook::class, 'of')) {
            return new Guestbook($item);
        }
        return Guestbook::of($item);
    }
}