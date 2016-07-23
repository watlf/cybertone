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
            $dataForm = $this->getFormData($request);

            /**
             * @var UserFilter $filter
             */
            $filter = new UserFilter();
            $form->setInputFilter($filter->getInputFilter());

            $form = $this->addValidatorRecord($form);

            $form->setData($dataForm);

            if ($form->isValid()) {
                $dataForm['password'] = $this->getHash($dataForm['password']);

                if (!empty($dataForm['logo'])) {
                    $dataForm['extension'] = $this->getExtension($dataForm['logo']);
                }

                $userId = $this->getConsumersRepository()->addConsumer($dataForm);

                if ($userId) {
                    if (isset($dataForm['extension'])) {
                        $this->saveConsumerAvatar($userId, $dataForm['extension'], $dataForm['logo']);
                    }
                    $this->flashMessenger()->addMessage('User was successfully added');
                } else {
                    $this->flashMessenger()->addMessage('Something went wrong, try again');
                }

                $this->redirect()->toRoute('user');
            }
        }

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $id = $this->params('id');
        $consumer = $this->getConsumersRepository()->findOneBy(array('id' => $id));

        /**
         * @var UserForm $form
         */
        $form = $this->getForm();

        if (is_null($consumer)) {
            $this->flashMessenger()->addMessage('User not found');
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

            /**
             * @var UserFilter $filter
             */
            $filter = new UserFilter($dbAdapter);

            $form->setInputFilter($filter->getInputFilter());

            $exclude = array(
                'exclude' => array(
                    'field' => 'id',
                    'value' => $id
                )
            );

            $form = $this->addValidatorRecord($form, $exclude);

            $form->setData($dataForm);

            if ($form->isValid()) {
                $dataForm['password'] = $this->getHash($dataForm['password']);
                if (!empty($dataForm['logo'])) {
                    $dataForm['extension'] = $this->getExtension($dataForm['logo']);
                }

                $userId = $this->getConsumersRepository()->editConsumer($dataForm, $id);

                if ($userId) {
                    if (isset($dataForm['extension'])) {
                        $this->saveConsumerAvatar($userId, $dataForm['extension'], $dataForm['logo']);
                    }
                    $this->flashMessenger()->addMessage('User was successfully updated');
                } else {
                    $this->flashMessenger()->addMessage('Something went wrong, try again');
                }

                $this->redirect()->toRoute('home');
            }

            $form->setData($dataForm);
        }

        return array(
            'form' => $form
        );
    }

    public function deleteAction()
    {
        $result = $this->getConsumersRepository()->deleteConsumer(
            $this->params('id')
        );

        if ($result) {
            $this->flashMessenger()->addMessage('User was successfully deleted');
        } else {
            $this->flashMessenger()->addMessage('Something went wrong, try again');
        }

        $this->redirect()->toRoute('home');
    }

    /**
     * @param UserForm $form
     * @param array $exclude
     * @return UserForm
     */
    private function addValidatorRecord(UserForm $form, $exclude = array())
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $options = array(
            'table' => 'consumers',
            'field' => 'login',
            'adapter' => $dbAdapter
        );

        $options = $exclude ? $options + $exclude : $options;

        $form->getInputFilter()->get('login')->getValidatorChain()->addValidator(
            new NoRecordExists($options)
        );

        $options['field'] = 'email';

        $form->getInputFilter()->get('email')->getValidatorChain()->addValidator(
            new NoRecordExists($options)
        );

        return $form;
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
        $avatar = $this->getConsumerAvatarById($userId);

        if ($avatar) {
            array_map('unlink', $avatar);
        }

        $filter = new Rename(array(
            "target"    => sprintf("%s/uploads/consumers/avatar/%d.%s", APP_PUBLIC, $userId, $extension)
        ));
        
        return $filter->filter($fileInfo);
    }

    /**
     * @param $userId
     * @return array
     */
    private function getConsumerAvatarById($userId)
    {
        $fileName = sprintf("%s/uploads/consumers/avatar/%d.*", APP_PUBLIC, $userId);

        return glob($fileName);
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