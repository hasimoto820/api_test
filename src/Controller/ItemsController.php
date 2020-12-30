<?php
declare(strict_types=1);

namespace App\Controller;


#use Cake\Network\Http\Client;   # api用
use Cake\Http\Client;   # api用




/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */



     public function beforeFilter(\Cake\Event\EventInterface $event)
     {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐことができます
        //$this->Auth->allow(['receivepost', 'sendposta']);
        if ($this->params['action'] == 'sendposta') {
          $this->Security->csrfCheck = false;
          $this->Security->validatePost = false;
        }
     }













     public function index()
     {
       $items = $this->paginate($this->Items);

       $this->set(compact('items'));
     }







    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('item'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $item = $this->Items->newEmptyEntity();
        if ($this->request->is('post')) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $this->set(compact('item'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $this->set(compact('item'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $item = $this->Items->get($id);
        if ($this->Items->delete($item)) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }



    public function searchAddress() {
        $url = 'http://zipcloud.ibsnet.co.jp/api/search';
        $data = [
            'zipcode' => $this->request->getQuery('郵便番号')
        ];

        $http = new Client();
        $response = $http->get($url, $data);

        $body = json_decode($response->body());

        $results = [];
        if ($body->status == 200 && $body->message == null && $body->results != null) {
            foreach ($body->results as $result) {
                $tmp = [
                    '郵便番号' => $result->zipcode,
                    '都道府県コード' => $result->prefcode,
                    '住所１' => $result->address1,
                    '住所２' => $result->address2,
                    '住所３' => $result->address3,
                    '住所かな１' => $result->kana1,
                    '住所かな２' => $result->kana2,
                    '住所かな３' => $result->kana3
                ];
                array_push($results, $tmp);
            }
        } else {
            $this->response->body(json_encode($body->message));
        }

        $this->set(compact('results'));
        $this->set('_serialize', ['results']);
    }





    public function apiindex()
    {
      $this->loadModel('Items');
      $items = $this->Items->find('all');
      $this->set(compact('items'));

#       $this->set('message', 'これはサンプルです');

      // JSON で出力
      $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', 'items')
        ->setOption('jsonOptions', JSON_FORCE_OBJECT);
    }







   public function apiview($id = null)
   {

       $this->loadModel('Items');
       $item = $this->Items->get($id, [
           'contain' => [],
       ]);
       $this->set(compact('item'));


       // JSON で出力
       $this->viewBuilder()
         ->setClassName('Json')
         ->setOption('serialize', 'item')
         ->setOption('jsonOptions', JSON_FORCE_OBJECT);

   }





   public function fileget()
   {


   }





   public function streampost()
   {


   }

   public function receivepost(){

     if(!empty($this->request->data)){


       var_dump('Hello world');
       //キーがPOST内容になっているので
       foreach($this->request->data as $key => $value){
         $data = $key;
       }
       //PHPで使える配列に。
       $data = json_decode($data,true);

       $this->set(compact('data'));
       #print($Data);

       // JSON で出力
       $this->viewBuilder()
         ->setClassName('Json')
         ->setOption('serialize', 'data')
         ->setOption('jsonOptions', JSON_FORCE_OBJECT);

     } else {
       var_dump('Null inside....');
     }

    }



    public function sendpost(){

            $url = 'http://localhost/api_test/items/receivepost';
            $data = array(
                'msg' => 'message hihihihi',
                'name' => 'triumph'
            );

      $data = http_build_query($data, "", "&");

      $header = array(
      "Content-Type: application/x-www-form-urlencoded",
      "Content-Length: ".strlen($data)
      );

      $options =array(
         'http' =>array(
            'method' => 'POST',
            'header' => implode("\r\n", $header),
            'content' => $data
         )
      );


      $contents =file_get_contents($url, false, stream_context_create($options));


            //var_dump($http_response_header);

            echo $html;
             $this->set('html',$html);
    }



    public function sendposta(){



      if($this->request->is('post')) {

        $data = [
          'foo' => 'bar',
          'baz' => 'qux'
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
          CURLOPT_URL => 'http://localhost/api_test/items/receivepost',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => http_build_query($data, '', '&'),
          CURLINFO_HEADER_OUT => true,
        ]);
        var_dump('$ch のデバッグ');
        print_r($data);
        $html = curl_exec($ch);


        $this->set('html',$html);
      } else {

        $html = '';
        $this->set('html',$html);
      }



      print($html);



    }







    public function receivepostb(){


     }


    public function sendpostb(){



      $http = new Client();

      // application/x-www-form-urlencoded エンコードデータを POST リクエストで送信
      $http = new Client();
      $response = $http->post('http://localhost/api_test/items/receivepost', [
        'title' => 'testing',
        'body' => 'content in the post'
      ]);
      var_dump($response);

    }









    public function receivepostc(){



      $this->loadModel('Items');
      $item = $this->Items->get(2, [
          'contain' => [],
      ]);
      $this->set(compact('item'));

      // JSON で出力
      $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', 'item')
        ->setOption('jsonOptions', JSON_FORCE_OBJECT);


     }


    public function sendpostc(){


      // application/x-www-form-urlencoded エンコードデータを POST リクエストで送信
      $http = new Client();
      $response = $http->get('http://localhost/api_test/items/receivepostc');

      $json = $response->getJson();



      #var_dump($response);
      var_dump($json);


    }







        public function receivepostd(){


         }


        public function sendpostd(){



          $http = new Client();

          // 単純な GET
          $response = $http->get('https://www.yahoo.co.jp/');



        }






}
