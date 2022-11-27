<?php

namespace App\Observers;

use App\Models\Historiae\ChangeLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ChangesObserver
{
    /**
     * Listen to the Model saving event.
     *
     * @param   Model   $object
     * @return  void
     */
    public function saving(Model $object)
    {
        $after = $object::find($object->getKey());
        ChangeLog::create([
            'model' => get_class($object),
            'payload_after' => $after ? $after->setHidden([]) : [],
            'payload_before' => $object->setHidden([]),
            'created' => ! $object->exists,
            'user_id' => Auth::id(),
        ]);
    }
}
