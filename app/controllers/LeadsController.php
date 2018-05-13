<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class LeadsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for leads
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Leads', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $leads = Leads::find($parameters);
        if (count($leads) == 0) {
            $this->flash->notice("The search did not find any leads");

            $this->dispatcher->forward([
                "controller" => "leads",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $leads,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a lead
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $lead = Leads::findFirstByid($id);
            if (!$lead) {
                $this->flash->error("lead was not found");

                $this->dispatcher->forward([
                    'controller' => "leads",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $lead->id;

            $this->tag->setDefault("id", $lead->id);
            $this->tag->setDefault("first_name", $lead->first_name);
            $this->tag->setDefault("last_name", $lead->last_name);
            $this->tag->setDefault("email_address", $lead->email_address);
            $this->tag->setDefault("phone", $lead->phone);
            $this->tag->setDefault("address", $lead->address);
            $this->tag->setDefault("square_footage", $lead->square_footage);
            
        }
    }

    /**
     * Creates a new lead
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'index'
            ]);

            return;
        }

        $lead = new Leads();
        $lead->first_name = $this->request->getPost("first_name");
        $lead->last_name = $this->request->getPost("last_name");
        $lead->email_address = $this->request->getPost("email_address");
        $lead->phone = $this->request->getPost("phone");
        $lead->address = $this->request->getPost("address");
        $lead->square_footage = $this->request->getPost("square_footage");

        if (!$lead->save()) {
            foreach ($lead->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("lead was created successfully");

        $this->dispatcher->forward([
            'controller' => "leads",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a lead edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $lead = Leads::findFirstByid($id);

        if (!$lead) {
            $this->flash->error("lead does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'index'
            ]);

            return;
        }

        $lead->firstName = $this->request->getPost("first_name");
        $lead->lastName = $this->request->getPost("last_name");
        $lead->emailAddress = $this->request->getPost("email_address");
        $lead->phone = $this->request->getPost("phone");
        $lead->address = $this->request->getPost("address");
        $lead->squareFootage = $this->request->getPost("square_footage");
        $lead->completedOn = $this->request->getPost("completed_on");
        

        if (!$lead->save()) {

            foreach ($lead->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'edit',
                'params' => [$lead->id]
            ]);

            return;
        }

        $this->flash->success("lead was updated successfully");

        $this->dispatcher->forward([
            'controller' => "leads",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a lead
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $lead = Leads::findFirstByid($id);
        if (!$lead) {
            $this->flash->error("lead was not found");

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'index'
            ]);

            return;
        }

        if (!$lead->delete()) {

            foreach ($lead->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("lead was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "leads",
            'action' => "index"
        ]);
    }

    public function registerAction()
    {

      if (!$this->request->isPost()) {
        $this->dispatcher->forward([
          'controller' => "index",
          'action' => 'index'
        ]);

        return;
      }

      $lead = new Leads();
      $lead->first_name = $this->request->getPost("first_name");
      $lead->last_name = $this->request->getPost("last_name");
      $lead->email_address = $this->request->getPost("email_address");
      $lead->phone = $this->request->getPost("phone");
      $lead->address = $this->request->getPost("address");
      if($this->request->getPost("square_footage") > 0) {
        $lead->square_footage = $this->request->getPost("square_footage");
      }
      else {
        $lead->square_footage = 0;
      }

      if (!$lead->save()) {
        foreach ($lead->getMessages() as $message) {
          $this->flash->error($message);
        }

        return;
      }

      $this->flash->success("Thank you!");
      $this->response->redirect('/success');
      $this->view->disable();

      return;

    }

}
