<?php

namespace Tests\Feature;

use App\User;
use Ergare17\Articles\Models\Article;
use Illuminate\Support\Facades\View;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
    }

    public function testShowAllArticles()
    {
        // 3 parts

        // 1) Preparo el test
        // 2) Executo el codi que vull provar
        // 3) Comprovo: assert

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $articles = factory(Article::class, 50)->create();

        $response = $this->get('/articles_php');
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('articles::list_article');

        foreach ($articles as $article) {
            $response->assertSeeText($article->title);
            $response->assertSeeText($article->title);
        }
    }

    /**
     * @group todo
     */
    public function testShowAnArticle()
    {
        // Preparo
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        // Executo
        $response = $this->get('/articles_php/'.$article->id);
        // Comprovo
        $response->assertStatus(200);
        $response->assertSuccessful();
        $response->assertViewIs('articles::show_article');
        $response->assertViewHas('article');

        // assertSeeText() -> mira si apareix el text que li passes, a la web
        $response->assertSeeText($article->title);
        $response->assertSeeText($article->description);
        $response->assertSeeText('Article:');
    }

    /**
     * @group todo
     */
    public function testNotShowAnArticle()
    {
        // Executo
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/articles_php/999999');
        // Comprovo
        $response->assertStatus(404);
    }

    //laravel eloquent: retrieving models

    public function testShowCreateArticleForm()
    {
        // Preparo
        $user = factory(User::class)->create();
        $this->actingAs($user);
        // Executo
        $response = $this->get('/articles_php/create');
        // Comprovo
        $response->assertStatus(200);
        $response->assertViewIs('articles::create_article');
        $response->assertSeeText('Create Article');
    }

    public function testShowEditArticleForm()
    {
        // Preparo
        $article = factory(Article::class)->create();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        // Executo
        $response = $this->get('/articles_php/edit/'.$article->id);
        // Comprovo
        $response->assertStatus(200);
        $response->assertViewIs('articles::edit_article');
        $response->assertSeeText('Edit Article');

        $responseFinal = $this->get('/articles_php/'.$article->id);

        $responseFinal->assertSeeText($article->title);
        $responseFinal->assertSeeText($article->description);
    }

    public function testStoreArticleForm()
    {
        // Preparo
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $article = factory(Article::class)->make();
        // Executo
        $response = $this->post('/articles_php', [
            'title' => $article->title,
            'description' => $article->description
        ]);
        //Comprovo
        $this->assertDatabaseHas('articles', [
            'title' => $article->title,
            'description' => $article->description,
        ]);
    }

    public function testUpdateArticleForm()
    {
        // Preparo
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $article = factory(Article::class)->create();
        // Executo
        $newArticle = factory(Article::class)->make();
        $response = $this->put('/articles_php/' . $article->id, [
            'title' => $newArticle->title,
            'description' => $newArticle->description,
        ]);
        // Comprovo
        $response->assertRedirect('articles_php/edit/'.$article->id);

        $this->assertDatabaseHas('articles', [
            'id' =>  $article->id,
            'title' => $newArticle->title,
            'description' => $newArticle->description,
        ]);

        $this->assertDatabaseMissing('articles', [
            'id' =>  $article->id,
            'title' => $article->title,
            'description' => $article->description,
        ]);
    }

    public function testDeleteArticle()
    {
        // Preparo
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $article = factory(Article::class)->create();
//        dump(Article::all()->count());
        // Executo
//        $response = $this->delete('/articles_php/' . $article->id, [
//            "csrf-token" => csrf_token()
//        ]);
        $response = $this->call('DELETE', '/articles_php/' . $article->id);

        // Comprovo
        $this->assertDatabaseMissing('articles', [
            'title' => $article->title,
            'description' => $article->description
        ]);

        $response->assertRedirect('articles_php');
//        $response->assertSeeText('Deleted ok!');
    }
}
