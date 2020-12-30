<?php
declare(strict_types=1);

namespace App\Controller;
#use Cake\Network\Http\Client;
use Cake\Http\Client;

/**
 * Topics Controller
 *
 * @property \App\Model\Table\TopicsTable $Topics
 * @method \App\Model\Entity\Topic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TopicsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */





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
















    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $topics = $this->paginate($this->Topics);

        $this->set(compact('topics'));
    }

    /**
     * View method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $topic = $this->Topics->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('topic'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $topic = $this->Topics->newEmptyEntity();
        if ($this->request->is('post')) {
            $topic = $this->Topics->patchEntity($topic, $this->request->getData());
            if ($this->Topics->save($topic)) {
                $this->Flash->success(__('The topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The topic could not be saved. Please, try again.'));
        }
        $users = $this->Topics->Users->find('list', ['limit' => 200]);
        $this->set(compact('topic', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $topic = $this->Topics->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $topic = $this->Topics->patchEntity($topic, $this->request->getData());
            if ($this->Topics->save($topic)) {
                $this->Flash->success(__('The topic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The topic could not be saved. Please, try again.'));
        }
        $users = $this->Topics->Users->find('list', ['limit' => 200]);
        $this->set(compact('topic', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Topic id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $topic = $this->Topics->get($id);
        if ($this->Topics->delete($topic)) {
            $this->Flash->success(__('The topic has been deleted.'));
        } else {
            $this->Flash->error(__('The topic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
