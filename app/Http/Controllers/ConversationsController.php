<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repository\ConversationRepository;
use Illuminate\Auth\AuthManager;
use App\Http\Requests\StoreMessageRequest;
class ConversationsController extends Controller
{
    
	/**
	 *	@var ConversationRepository 
	 */

    private $r ;
    
    /**
	 *	@var AuthManager
	 */

    private $auth;
	

	public function __construct(ConversationRepository $r, AuthManager $auth)
	{
			$this->r=$r;
			$this->auth=$auth;

	}


    public function index()
    {

     	return view('conversations/index',[

     		'users' => $this->r->getConversations($this->auth->user()->id)
    		


     	]);
     
    }
    public function show(User $user)
	    {
            return view('conversations/show',[

     		'users' => $this->r->getConversations($this->auth->user()->id),
     		'user' => $user,
            'messages'=> $this->r->getMessagesFor($this->auth->user()->id,$user->id)
                            ->paginate(2)
                            

     	]);
 
        }
        public function store(User $user, StoreMessageRequest $request)
        {
            $this->r->createMessage(
                                    $request->get('content'),
                                    $this->auth->user()->id,
                                    $user->id
                                    );
            return redirect(route('conversations.show',['id'=>$user->id])
            );

        }
}
