<?php

namespace App\Livewire;

use Aws\Exception\AwsException;
use Livewire\Component;
use Aws\Sns\SnsClient;
use Aws\Laravel\AwsFacade as AWS;

class Support extends Component
{
    public $description;

    protected $rules = [
        'description' => 'required|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        $message = 'Bug report: ' . $this->description;

        try {
            $aws = AWS::createClient('sns');
            $aws->publish([
                'Message' => $message,
                'TargetArn' => 'arn:aws:sns:us-east-1:218825106566:sns_notification',
            ]);
        } catch (AwsException $e) {
            // Output error message if fails
            error_log($e->getMessage());
        }

        session()->flash('message', 'Bug report sent successfully.');

        $this->reset('description');
    }

    public function render()
    {
        return view('livewire.support')
            ->layout('layouts.app');;
    }
}
