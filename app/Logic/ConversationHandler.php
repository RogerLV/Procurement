<?php

namespace App\Logic;


use App\Logic\LoginUser\LoginUserKeeper;
use App\Models\Conversation;

class ConversationHandler
{
    public static function getByReferenceIns($referenceIns)
    {
        return Conversation::where([
            ['referenceTable', '=', $referenceIns->table],
            ['referenceID', '=', $referenceIns->id],
        ])->orderBy('id', 'DESC')->get();
    }

    public static function add($referenceIns, $content)
    {
        $conversation = new Conversation();
        $conversation->referenceTable = $referenceIns->table;
        $conversation->referenceID = $referenceIns->id;

        $loginUser = LoginUserKeeper::getUser();
        $conversation->lanID = $loginUser->getUserInfo()->lanID;
        $conversation->content = $content;
        $conversation->save();

        return $conversation;
    }
}