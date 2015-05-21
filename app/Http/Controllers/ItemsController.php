<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Items;
use App\UserItems;
use Goose\Client as GooseClient;
use Illuminate\Http\Request;
use \GuzzleHttp\Exception\RequestException;

class ItemsController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
    }

    public function index()
    {
        $items = $this->user()->items()->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // validate that the URL specified is correct.
        $this->validate($request, array('url' => 'required|url'));

        $url = $request->get('url');

        // Find the main item, or create it.
        $item = Items::find(md5($url));
        if ($item == null) {
            try {
                $item = $this->createItem($url);
            } catch (RequestException $e) {
                // Something went wrong requesting the URL given. So give the error to the user.
                return \Redirect::back()->withInput()->withErrors(['url' => $e->getMessage()]);
            }
        }

        // create our user item if it doesn't exist.
        $user_item = UserItems::find(md5($url));
        if ($user_item == null) {
            $user_item = new UserItems();
            $user_item->user_id = $this->user()->id;
            $user_item->item_id = $item->id;
            $user_item->save();
        }
        return \Redirect::route('items.index');
    }

    protected function createItem($url)
    {
        $goose = new GooseClient();
        $article = $goose->extractContent($url);
        $articleText = $article->getCleanedArticleText();

        $item = new Items();
        $item->id = md5($url);
        $item->url = $url;
        $item->content = $articleText;
        $item->status = Items::STATUS_FETCHED;
        $item->save();

        return $item;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
//        $item = \App\Items::findOrFail($id);
//        return view('items.show', compact('item'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * @return \App\User
     */
    protected function user()
    {
        return \Auth::user();
    }

}
