<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.07.16
 * Time: 18:25
 */

namespace Application\Controller;


use Application\Filter\UserFilter;
use Application\Form\UserForm;
use Zend\Filter\File\Rename;
use Zend\Http\Request;
use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\Db\NoRecordExists;
use Zend\View\Model\ViewModel;

class UserController extends AbstractExtendedController
{
    public function indexAction()
    {
        $flashMessenger = $this->flashMessenger();

        $messages = ($flashMessenger->hasMessages()) ? $flashMessenger->getMessages() : '';

        $form = $this->getForm();

        $form->setAttribute('action', 'user/add');

        return array(
            'form' => $form,
            'messages' => $messages
        );
    }

    public function addAction()
    {
        /**
         * @var UserForm $form
         */
        $form = $this->getForm();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dataForm = $this->getFormData($request);

            $filter = new UserFilter();

            $form->setInputFilter($filter->getInputFilter());

            $form->getInputFilter()->get('login')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => 'consumers',
                    'field' => 'login',
                    'adapter' => $dbAdapter
                ))
            );

            $form->getInputFilter()->get('email')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => 'consumers',
                    'field' => 'email',
                    'adapter' => $dbAdapter
                ))
            );

            $form->setData($dataForm);

            if ($form->isValid()) {
                $repository = $this->getConsumersRepository();

                $dataForm['password'] = $this->getHash($dataForm['password']);
                $dataForm['extension'] = $this->getExtension($dataForm['logo']);

                $userId = $repository->addConsumer($dataForm);

                $upload = $this->saveConsumerAvatar($userId, $dataForm['extension'], $dataForm['logo']);

                if (isset($upload['error']) && 0 === $upload['error']) {
                    $this->flashMessenger()->addMessage('User was successfully added');
                } else {
                    $this->flashMessenger()->addMessage('Something went wrong, try again');
                }

                $this->redirect()->toRoute('user');
            }
        }
        
        $result = array(
            'form' => $form
        );

        return $result;
    }

    public function editAction()
    {
        $id = $this->params('id');
        $consumer = $this->getConsumersRepository()->findOneBy(array('id' => $id));
        $form = $this->getForm();

        if (is_null($consumer)) {
            $this->flashMessenger()->addMessage('User not found');
            //$this->redirect()->toRoute('home');
        } else {
            $form->setData(array(
                'login' => $consumer->getLogin(),
                'email' => $consumer->getEmail(),
                'accountExpired' => $consumer->getAccountExpired()->format('Y-m-d'),
                'groupId' => $consumer->getGroup(),
            ));
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dataForm = $this->getFormData($request);

            $filter = new UserFilter($dbAdapter);

            $form->setInputFilter($filter->getInputFilter());

            $form->getInputFilter()->get('login')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => 'consumers',
                    'field' => 'login',
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id
                    ),
                    'adapter' => $dbAdapter
                ))
            );

            $form->getInputFilter()->get('email')->getValidatorChain()->addValidator(
                new NoRecordExists(array(
                    'table' => 'consumers',
                    'field' => 'email',
                    'exclude' => array(
                        'field' => 'id',
                        'value' => $id
                    ),
                    'adapter' => $dbAdapter
                ))
            );

            $form->setData($dataForm);

            if ($form->isValid()) {
                $repository = $this->getConsumersRepository();

                $dataForm['password'] = $this->getHash($dataForm['password']);
                $dataForm['extension'] = $this->getExtension($dataForm['logo']);

                $userId = $repository->editConsumer($dataForm, $id);

                $upload = $this->saveConsumerAvatar($userId, $dataForm['extension'], $dataForm['logo']);

                if (isset($upload['error']) && 0 === $upload['error']) {
                    $this->flashMessenger()->addMessage('User was successfully updated');
                } else {
                    $this->flashMessenger()->addMessage('Something went wrong, try again');
                }

                $this->redirect()->toRoute('user');
            }

            $form->setData($dataForm);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * TODO need refactor!!!
     * @param int $userId
     * @param $extension
     * @param array $fileInfo
     * @return array|string
     */
    private function saveConsumerAvatar($userId, $extension, array $fileInfo)
    {
        $filter = new Rename(array(
            "target"    => sprintf("%s/uploads/consumers/avatar/%d.%s", APP_PUBLIC, $userId, $extension)
        ));
        
        return $filter->filter($fileInfo);
    }

    /**
     * @param array $logoData
     * @return mixed
     */
    private function getExtension(array $logoData)
    {
        return pathinfo(basename($logoData["name"]), PATHINFO_EXTENSION);
    }

    /**
     * TODO need refactor!!!
     * @param $password
     * @return string
     */
    private function getHash($password)
    {
        $bcrypt = new Bcrypt(array(
            'salt' => 'random value hash'
        ));

        return $bcrypt->create($password);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getFormData(Request $request)
    {
        return array_merge_recursive(
            $request->getPost()->toArray(),
            $request->getFiles()->toArray()
        );
    }

    /**
     * @return UserForm
     */
    private function getForm()
    {
        $form = new UserForm();

        $groups = $this->getGroupsRepository()->getGroups();

        $form->get('groupId')->setOptions(array(
            'value_options' => array_merge(array('Select'), $groups)
        ));

        return $form;
    }
}