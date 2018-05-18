<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class LeadsController extends ControllerBase
{

    /**
     *
     */
    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Leads', $_POST);
            $this->persistent->parameters = $query->getParams();
        }
        else {
          if ($this->request->getQuery("page", "int") > 1) {
            $numberPage = $this->request->getQuery("page", "int");
          }
          else {
            $this->persistent->parameters = null;
          }
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
     * Searches for leads
     */
    public function searchAction()
    {
      $this->persistent->parameters = null;
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
                $this->flash->error("Lead was not found");

                $this->dispatcher->forward([
                    'controller' => "leads",
                    'action' => 'search'
                ]);

                return;
            }

            $this->view->id = $lead->id;

            $this->tag->setDefault("id", $lead->id);
            $this->tag->setDefault("first_name", $lead->first_name);
            $this->tag->setDefault("last_name", $lead->last_name);
            $this->tag->setDefault("email_address", $lead->email_address);
            $this->tag->setDefault("phone", $lead->phone);
            $this->tag->setDefault("address", str_replace('<br />',"\n",$lead->address));
            $this->tag->setDefault("square_footage", $lead->square_footage);
            $this->view->created_on = $lead->created_on;
            $this->view->completed_on = $lead->completed_on;

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
        $lead->address = nl2br($this->request->getPost("address"));
        $lead->session_id = $this->getDI()->getSession()->get('user-id');
        //make sure square footage has some value even if blank or not a number
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

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("Lead was created successfully");

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
            $this->flash->error("Lead does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'index'
            ]);

            return;
        }

        $lead->firstName = $this->request->getPost("first_name");
        $lead->lastName = $this->request->getPost("last_name");
        $lead->email_address = $this->request->getPost("email_address");
        $lead->phone = $this->request->getPost("phone");
        $lead->address = $this->request->getPost("address");
        //Let's make sure square footage has some value even if blank or not a number
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

            $this->dispatcher->forward([
                'controller' => "leads",
                'action' => 'edit',
                'params' => [$lead->id]
            ]);

            return;
        }

        $this->flash->success("Lead was updated successfully");

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
            $this->flash->error("Lead was not found");

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

    /**
     * Landing page
     * Registration flow
     *
     */
    public function registerAction()
    {

      if (!$this->request->isPost()) {

        $this->dispatcher->forward([
          'controller' => "index",
          'action' => 'index'
        ]);

        return;
      }
      //load lead if we had a partial capture
      if($this->request->getPost("session_id") != '') {
        $lead = Leads::findFirst("session_id = '" . $this->request->getPost("session_id") . "'");
      }
      else {
        $lead = new Leads();
      }
      $lead->first_name = $this->request->getPost("first_name");
      $lead->last_name = $this->request->getPost("last_name");
      $lead->email_address = $this->request->getPost("email_address");
      $lead->phone = $this->request->getPost("phone");
      $lead->address = nl2br($this->request->getPost("address"));
      $lead->completed_on = $this->created_on = date("Y-m-d H:i:s");
      //Let's make sure square footage has some value even if blank or not a number
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

      //Destroy the session now in case we start over!
      $this->session->destroy();

      $this->flash->success("Thank you!");
      $this->response->redirect('/success');
      $this->view->disable();

      return;

    }

    /**
     * Ajax listener
     *
     */
    public function ajaxRegisterAction() {

      if (!$this->request->isPost() || !$this->request->isAjax()) {

        $this->dispatcher->forward([
          'controller' => "index",
          'action' => 'index'
        ]);

        return;
      }

      $this->view->disable();
      $data = $this->request->getJsonRawBody();
      $field = $data->field;

      if ($data->session) {
        $lead = Leads::findFirst("session_id = '" . $data->session . "'");
        $lead->$field = $data->value;
      }
      else {
        $lead = new Leads();
        $lead->$field = $data->value;
      }

      if (!$lead->save()) {

        foreach ($lead->getMessages() as $message) {
          $this->flash->error($message);
        }

      }

      //Create a new response
      $response = new \Phalcon\Http\Response();

      $resData = array(
        'session' => $lead->session_id,
      );

      //Content of the response
      $response->setContent(json_encode($resData));

      //Return the response
      return $response;

    }

}
