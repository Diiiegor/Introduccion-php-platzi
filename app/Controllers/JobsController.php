<?php


namespace App\Controllers;


use App\Models\Job;
use Respect\Validation\Validator as v;


class JobsController extends BaseController
{
    public function getAddJobAction($request)
    {
        $responseMessage =null;
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();
            $jobvalidator = v::key('title', v::stringType()->notEmpty())
                ->key('description', v::stringType()->notEmpty());

            try {
                $jobvalidator->assert($postData);
                $postData = $request->getParsedBody();
                $job = new Job();
                $job->title = $postData['title'];
                $job->description = $postData['description'];
                $files = $request->getUploadedFiles();
                $logo = $files['logo'];

                if($logo->getError() == UPLOAD_ERR_OK){
                    $filename = $logo->getClientFileName();
                    $logo->moveTo("uploads/$filename");
                    $job->file_name = $filename;
                }

                $job->save();
                $responseMessage = 'Saved';
            } catch (\Exception $e){
                $responseMessage = $e->getMessage();
               // var_dump($e->getMessage());
            }
            //var_dump();


        }
        return $this->renderHTML('addJob.twig',[
            'responseMessage'=>$responseMessage
        ]);
    }
}