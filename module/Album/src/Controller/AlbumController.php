<?php

namespace Album\Controller;

use Album\Entity\Album;
use Album\Form\AddAlbumForm;
use Album\Form\EditAlbumForm;
use Doctrine\ORM\EntityManager;
use Laminas\Form\FormElementManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private EntityManager $entityManager;
    private FormElementManager $formManager;

    public function __construct(
        EntityManager $entityManager,
        FormElementManager $formManager
    ) {
        $this->entityManager = $entityManager;
        $this->formManager   = $formManager;
    }

    public function indexAction()
    {
        $page = (int) $this->params()->fromQuery('page', 1);
        $perPage = 5;

        $repository = $this->entityManager->getRepository(Album::class);
        $doctrinePaginator = $repository->findPaginated($page, $perPage);

        $totalItems = count($doctrinePaginator);
        $totalPages = (int) ceil($totalItems / $perPage);

        $albums = iterator_to_array($doctrinePaginator);

        return new ViewModel([
            'albums'      => $albums,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ]);
    }

    public function addAction()
    {
        // get form from FormElementManager
        $form = $this->formManager->get(AddAlbumForm::class);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                // returns populated Album object!
                $album = $form->getData();

                $this->entityManager->persist($album);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(['form' => $form]);
    }

    public function editAction()
    {
        $id    = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->find(Album::class, $id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $form = $this->formManager->get(EditAlbumForm::class);

        // bind existing album - auto populates form fields!
        $form->bind($album);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {
                // $album is already updated by binding!
                // no need to manually set fields
                $this->entityManager->flush();

                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(['form' => $form, 'album' => $album]);
    }


    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $album = $this->entityManager->getRepository(Album::class)->find($id);

        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $confirm = $this->params()->fromPost('confirm');

            if ($confirm === 'yes') {
                $title = $album->getTitle();
                $this->entityManager->remove($album);
                $this->entityManager->flush();

                $flashMessenger = $this->flashMessenger();
                $flashMessenger->addMessage('Album "' . $title . '" was deleted successfully!', 'success');
            }

            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(['album' => $album]);
    }
}
