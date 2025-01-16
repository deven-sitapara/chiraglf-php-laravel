<?php

namespace App\Actions\TSR;

use App\Models\Tag;
use App\Models\TSR;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OpenTSR
{
    public function create(User $user, array $input): TSR
    {
        $tsr = new TSR([
            'file_id' => $input['file_id']
        ]);
        $tsr->save();

        $tag = Tag::query()
            ->where('project_id', $user->current_project_id)
            ->where('name', $input['name'])
            ->first();
        if ($tag) {
            throw ValidationException::withMessages([
                'name' => ['Tag with this name already exists.'],
            ]);
        }

        $tag = new Tag([
            'project_id' => $user->currentProject->id,
            'name' => $input['name'],
            'color' => $input['color'],
        ]);
        $tag->save();

        return $tag;
    }

    public static function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'color' => [
                'required',
                Rule::in(config('core.tag_colors')),
            ],
        ];
    }
}
