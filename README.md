#Form Helper


##Usage

###Create Class Form

```php
namespace Modules\Utilities\Http\Requests\BroadcastNews;

use App\Helpers\Form\Form;
use Modules\Common\Http\Requests\FormCommon;

class FormCreateBroadcast extends Form {

    public function __construct() {
        parent::__construct();
        $FormCommon = new FormCommon();
        $form_vendor_id = $FormCommon->getForm('vendor_id');
        $this->addForm($form_vendor_id);
        $form_office_id = $FormCommon->getForm('office_id');
        $this->addForm($form_office_id);

        $this->add('title', 'text', ['rules' => 'required'], ['label' => 'Header']);
        $this->add('publish_at', 'datepicker_timepicker', ['role' => 'required'], ['label' => 'Schedule']);

        $this->add('content', 'texteditor', ['rules' => 'required'], ['label' => 'News Content']);
        $this->add('image', 'upload', ['rules' => 'image'], [
            'label' => 'Header Picture',
            'accept' => 'image/*'
        ]);

        $this->add('status', 'select', [], [
            'class' => 'fixed-disabled',
            'disabled' => null
        ]);
        $status_data = array(
            array(
                'id' => 'draft',
                'text' => 'Draft',
            ),
            array(
                'id' => 'sent',
                'text' => 'Sent',
            ),
        );
        $this->setData('status', $status_data);
        $this->setValue('status', 'draft');
    }

    public function editProcess() {
        $this->add('id', 'text', ['rules' => 'required']);
    }

}


```