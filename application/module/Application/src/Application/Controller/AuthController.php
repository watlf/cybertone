<?php

namespace Application\Controller;

use Application\Form\AuthForm;
use Application\Filter\AuthFilter;

class AuthController extends AbstractExtendedController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $form = new AuthForm();

        $request = $this->getRequest();

        $result = array(
            'title' => 'Sign in',
            'authErrorMessage' => '',
            'form' => $form
        );

        /**
         * @var $authService \Zend\Authentication\AuthenticationService
         */
        $authService = $this->getAuthService();

        if ($authService->hasIdentity()) {
            $result = $this->redirect()->toRoute('home');
        } elseif ($request->isPost()) {
            $filter = new AuthFilter();

            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /**
                 * @var $authAdapter \Zend\Authentication\Adapter\Digest
                 */
                $authAdapter = $authService->getAdapter();

                $authAdapter->setIdentity(
                    $form->getInputFilter()->getValue('name')
                );

                $authAdapter->setCredential(
                    $form->getInputFilter()->getValue('password')
                );

                /**
                 * @var $authResult \Zend\Authentication\Result
                 */
                $authResult = $authAdapter->authenticate();

                if ($authResult->isValid()) {
                    $authService->getStorage()->write($request->getPost('name'));
                    $this->redirect()->toRoute('home');
                } else {
                    $result['authErrorMessage'] = implode(' ', $authResult->getMessages());
                }
            }
        }

        return $result;
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        /**
         * @var $authService \Zend\Authentication\AuthenticationService
         */
        $authService = $this->getAuthService();

        $authService->clearIdentity();

        $this->redirect()->toRoute('auth');
    }

}

