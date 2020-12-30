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
       $this->loadModel('Items');
       $items = $this->Items->find()
         ->select(['id', 'name', 'price'])
         ->all();
       $this->set(compact('items'));

       $this->set('message', 'これはサンプルです');

       // JSON で出力
       $this->viewBuilder()
         ->setClassName('Json')
         ->setOption('serialize', ['message', 'items'])
         ->setOption('jsonOptions', JSON_FORCE_OBJECT);
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





}
