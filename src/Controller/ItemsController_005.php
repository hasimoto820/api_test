<?php
declare(strict_types=1);

namespace App\Controller;


use Cake\Network\Http\Client;   # api用

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

       //キーがPOST内容になっているので
       foreach($this->request->data as $key => $value){
         $Data = $key;
       }
       //PHPで使える配列に。
       $Data = json_decode($Data,true);

       $this->set(compact('Data'));
       var_dump($Data);

       //再度json形式にして返す
       $this->set('data',$Data);
       $this->viewClass = 'Json';
       $this->set('_serialize',array('data'));

     }

    }



    public function sendpost(){

      $url = 'http://localhost/api_test/items/receivepost';
      $data = array(
          'msg' => 'message hihihihi',
          'name' => 'triumph'
      );

      $context = array(
          'http' => array(
              'method'  => 'POST',
              'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
              'content' => http_build_query($data)
          )
      );

      $html = file_get_contents($url, false, stream_context_create($context));

      //var_dump($http_response_header);

      echo $html;
       $this->set('html',$html);
    }






}
