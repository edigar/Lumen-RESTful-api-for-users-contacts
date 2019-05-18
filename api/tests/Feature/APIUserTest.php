<?php
namespace Tests\Feature;

use App\User;
use TestCase;

class APIUserTest extends TestCase
{

    private $users;
    private $user;
    
    public function setUp(): void {
        parent::setUp();
        $this->users = factory(User::class, 3)->create();
        $this->user = factory(User::class)->create();
    }

    protected function tearDown(): void {
        User::destroy(
            array(
                $this->users[0]->id,
                $this->users[1]->id,
                $this->users[2]->id,
                $this->user->id
            )
        );
    }

    /**
     * /users [GET]
     */
    public function testGetAllUsers() {

        $this->get("/api/users", [])
            ->seeStatusCode(200)
            ->seeJsonStructure([
            'data' => ['*' =>
                [
                    'name',
                    'email',
                    'celphone_number',
                    'phone_number',
                    'instagram_url',
                    'facebook_url',
                    'twitter_url',
                    'created_at',
                    'updated_at',
                    'links' => [
                        'self',
                    ]
                ]
            ],
            'total',
            'per_page',
            'current_page',
            'last_page',
            'from',
            'last_page_url',
            'next_page_url',
            'path',
            'prev_page_url',
            'to'
        ]);
    }

    /**
     * /users/id [GET]
     */
    public function testGetSingleUser(){

        $this->get("/api/users/" . $this->user->id)
            ->seeStatusCode(200)
            ->seeJsonContains(
            [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'celphone_number' => $this->user->celphone_number,
                'phone_number' => $this->user->phone_number,
                'instagram_url' => $this->user->instagram_url,
                'facebook_url' => $this->user->facebook_url,
                'twitter_url' => $this->user->twitter_url,
                'links' => [
                    'self' => '/api/users/' . $this->user->id
                ]
            ]
        );
        
    }

    /**
     * /users/id [GET]
     */
    public function testGetNonExistentResource() {
        $this->get("/api/users/0")->seeStatusCode(204);
    }

    /**
     * /users [POST]
     */
    public function testCreateValidUser() {
        $validUser = [
            'name' => 'Usuário',
            'email' => 'usuario@teste.com',
            'celphone_number'=> '(11)999999999',
            'phone_number'=> null,
            'instagram_url'=> "instagram.com/teste",
            'facebook_url'=> "facebook.com/teste",
            'twitter_url'=> "twitter/teste"
        ];

        $this->post("/api/users", $validUser)
            ->seeStatusCode(201)
            ->seeJsonContains(
            [
                'name' => $validUser['name'],
                'email' => $validUser['email'],
                'celphone_number' => $validUser['celphone_number'],
                'instagram_url' => $validUser['instagram_url'],
                'facebook_url' => $validUser['facebook_url'],
                'twitter_url' => $validUser['twitter_url'],
            ]
        );

        $this->seeInDatabase('users', ['email' => 'usuario@teste.com']);

        User::where('email', '=', 'usuario@teste.com')->delete();      
    }

    /**
     * /users [POST]
     */
    public function testCreateInvalidUser() {
        $invalidNullEmail = [
            'name' => 'Usuário',
            'email' => null,
            'celphone_number'=> '(11)999999999',
            'phone_number'=> '(11)999999999',
            'instagram_url'=> "instagram.com/teste",
            'facebook_url'=> "facebook.com/teste",
            'twitter_url'=> "twitter/teste"
        ];
        $invalidNullcel = [
            'name' => 'Usuário',
            'email' => 'usuario@teste.com',
            'celphone_number'=> null,
            'phone_number'=> '(11)999999999',
            'instagram_url'=> "instagram.com/teste",
            'facebook_url'=> "facebook.com/teste",
            'twitter_url'=> "twitter/teste"
        ];

        $this->post("/api/users", $invalidNullEmail)
            ->seeStatusCode(409)
            ->seeJsonEquals([
                'error' => 'Integrity constraint violation'
            ]);

        $this->post("/api/users", $invalidNullcel)
        ->seeStatusCode(409)
        ->seeJsonEquals([
            'error' => 'Integrity constraint violation'
        ]);;        
    }

    /**
     * /users [PUT]
     */
    public function testUpdateUser() {
        $invalidUserUpdate = [
            'email' => null,
        ];
        $validUserUpdate = [
            'instagram_url'=> "instagram.com/teste",
            'facebook_url'=> "facebook.com/teste",
            'twitter_url'=> "twitter/teste"
        ];

        $this->put('/api/users/' . $this->user->id, $invalidUserUpdate)
            ->seeStatusCode(409)
            ->seeJsonEquals([
                'error' => 'Integrity constraint violation'
            ]);
        
        $this->put('/api/users/' . $this->user->id, $validUserUpdate)
            ->seeStatusCode(200)
            ->seeJsonContains([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'celphone_number' => $this->user->celphone_number,
                'phone_number' => $this->user->phone_number,
                'instagram_url' => $validUserUpdate['instagram_url'],
                'facebook_url' => $validUserUpdate['facebook_url'],
                'twitter_url' => $validUserUpdate['twitter_url'],
            ]);
    }

    /**
     * /users [PUT]
     */
    public function testUpdateNonExistentResource() {
        $validUserUpdate = [
            'instagram_url'=> "instagram.com/teste",
        ];

        $this->put('/api/users/' . 0, $validUserUpdate)
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => 'resource not found'
            ]);
    }

    /**
     * /users/id [DELETE]
     */
    public function testDestroyUser() {
        $user = factory(User::class)->create();
        $this->delete('/api/users/' . $user->id)->seeStatusCode(200);
    }

    /**
     * /users/id [DELETE]
     */
    public function testDestroyNonExistentResource() {
        $this->delete('/api/users/' . 0)
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => 'resource not found'
            ]);
    }
}
