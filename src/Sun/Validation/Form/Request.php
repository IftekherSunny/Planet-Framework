<?php

namespace Sun\Validation\Form;

use Sun\Contracts\Http\Redirect;
use Sun\Contracts\Http\Response;
use Sun\Http\Request as HttpRequest;
use Sun\Contracts\Validation\Validator;

abstract class Request extends HttpRequest {

    /**
     * Array of all rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * @var \Sun\Contracts\Http\Response
     */
    protected $response;

    /**
     * @var \Sun\Contracts\Http\Redirect
     */
    protected $redirect;

    /**
     * @var \Sun\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * Create a new instance of the request class.
     *
     * @param Response    $response
     * @param Redirect    $redirect
     * @param Validator   $validator
     */
    public function __construct(Response $response, Redirect $redirect, Validator $validator)
    {
        $this->response = $response;

        $this->redirect = $redirect;

        $this->validator = $validator;
    }


    /**
     * Validate requested form.
     *
     * @return string
     */
    public function validate()
    {
        foreach($this->rules() as $key => $value) {
            $this->rules[$key] =  [$this->input($key), $value];
        }

        $validate = $this->validator->validate($this->rules);

        if ($validate->fails()) {
            if($this->isAjax()) {
                return $this->response->json(['errors' => $validate->errors()->all()], 403);
            }

            return $this->redirect->backWith('errors', $validate->errors()->all());
        }
    }

    /**
     * All rules associated with the request form.
     *
     * @return array
     */
    abstract protected function rules();
}
