<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Models\Conversation;
use App\Models\Project;
use Gate;

class ConversationController extends Controller
{
    public function add()
    {
        if (empty($referenceTable = request()->input('reference'))
            || empty($referenceID = request()->input('id'))
            || empty($content = request()->input('content'))) {
            throw new AppException('CON001', 'Data Error');
        }

        switch ($referenceTable) {
            case 'Projects':
                if (is_null($referenceIns = Project::find($referenceID))) {
                    throw new AppException('CON002', 'Incorrect Project Info.');
                }
                if (Gate::forUser($this->loginUser)->denies('project-visible', $referenceIns)) {
                    throw new AppException('CON003', 'ERROR_MESSAGE_NOT_AUTHORIZED');
                }

        }

        // add new conversation
        $conversation = new Conversation();
        $conversation->lanID = $this->loginUser->getUserInfo()->lanID;
        $conversation->content = $content;
        $referenceIns->conversation()->save($conversation);


        return response()->json([
            'status' => 'good',
            'info' => [
                'conversationIns' => $conversation,
                'userInfo' => $this->loginUser->getUserInfo(),
            ],
        ]);
    }
}
