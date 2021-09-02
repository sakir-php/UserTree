<?php

use App\Models\User;

if (!function_exists('random_code')) {

    function position($user_id){
        if(User::where('left_user', $user_id)->first()){
            return 'Left';
        }else if(User::where('right_user', $user_id)->first()){
            return 'Right';
        }else{
            return 'No Parent';
        }
    }


    function parent($user_id){
        if(User::where('left_user', $user_id)->first()){
            return User::where('left_user', $user_id)->first()->name;
        }else if(User::where('right_user', $user_id)->first()){
            return User::where('right_user', $user_id)->first()->name;
        }else{
            return 'No Parent';
        }
    }


    function user_tree($root_user_id){
        $data = [
            "l1"=>[
                "l1_u1" => null,
            ],

            "l2"=>[
                "l2_u1" => null,
                "l2_u2" => null,
            ],

            "l3"=>[
                "l3_u1" => null,
                "l3_u2" => null,
                "l3_u3" => null,
                "l3_u4" => null,
            ],
        ];

            // Level one
            $data["l1"]["l1_u1"] = User::find($root_user_id);

            // Level two
            if($data["l1"]["l1_u1"]){
                $data["l2"]["l2_u1"] = $data["l1"]["l1_u1"]->leftChild;
                $data["l2"]["l2_u2"] = $data["l1"]["l1_u1"]->rightChild;
            }

            //Level three left group
            if($data["l2"]["l2_u1"]){
                $data["l3"]["l3_u1"] = $data["l2"]["l2_u1"]->leftChild;
                $data["l3"]["l3_u2"] = $data["l2"]["l2_u1"]->rightChild;
            }

            // Level three right group
            if($data["l2"]["l2_u2"]){
                $data["l3"]["l3_u3"] = $data["l2"]["l2_u2"]->leftChild;
                $data["l3"]["l3_u4"] = $data["l2"]["l2_u2"]->rightChild;
            }

        return $data;
    }

    function carry_increment($child_id = 1, $carry = 0){
        $user = App\Models\User::where('left_user', $child_id)->orWhere('right_user', $child_id)->first();

        while($user != null) {
            $user->carry += $carry;
            $user->save();
            //echo "<h1></h1>Parent : ". $user->name."</h1> <br>";

            $user = App\Models\User::where('left_user', $user->id)->orWhere('right_user', $user->id)->first();
        }
    }

    function tfunction(User $parent){
        if($parent->left_user == null || $parent->right_user == null){
            return $parent;
        }
        if($parent->left_user != null && $parent->right_user != null){
            $parent_left = $parent->childs()->where('left_user', null)->first();
            $parent_right = $parent->childs()->where('right_user', null)->first();
            if($parent_left != null && $parent_right == null){
                return $parent_left;
            }else if($parent_left == null && $parent_right != null){
                return $parent_right;
            }else if($parent_left != null && $parent_right != null){
                if($parent_left->id <= $parent_right->id){
                    return $parent_left;
                }else{
                    return $parent_right;
                }
            }else{
                //Parent not found who have left/right side empty.
                //dd("Parent not found");
                foreach($parent->childs as $ch){
                    // dd($ch);
                    tfunction($ch);
                }
            }
            //dd($parent);
        }

    }
}
