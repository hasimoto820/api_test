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






     public function getjson() {


     }







     public function searchaddress() {



       // 連想配列用意
       $data = [
           'tokyo' => [
               '品川',
               '五反田',
               '三軒茶屋',
               '四谷'
           ],
           'kanagawa' => [
               '横浜',
               '相模原',
               '湘南',
               '鎌倉'
           ],
           'saitama' => [
               '所沢',
               '狭山',
               '川口',
               '浦和',
               '小手指',
               '飯能'
           ]
       ];

       // Origin null is not allowed by Access-Control-Allow-Origin.とかのエラー回避の為、ヘッダー付与
//       header("Access-Control-Allow-Origin: *");

//       echo json_encode($array);

       header('Content-Type: application/json, Access-Control-Allow-Origin: *');
       $data = json_encode($data);
       $data = mb_convert_encoding($data, "UTF-8", "EUC-JP");
       echo $data;



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
