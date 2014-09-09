<?php

    if (!defined ('BASEPATH'))
            exit ('No direct script access allowed');

    /**
     * Threaded
     * 
     * This class will help us to create alignment of comments in desired format.
     * Each reply corresponding to a comment must be properly aligned to it. 
     * 
     * @author Ankit Vishwakarma <ankitvishwakarma@sify.com>
     */
    class Threaded {

            public $parents = array();
            public $children = array();
            public $arranged = '';

            /**
             * @param array $comments
             */
            public function arrange ($comments)
            {
                    foreach ($comments as $comment) {

                            if ($comment->parent_id == '')
                            {
                                    $this->parents[$comment->id][] = $comment;
                            }
                            else
                            {
                                    $this->children[$comment->parent_id][] = $comment;
                            }
                    }

                    return $this->print_comments ();
            }

            private function tabulate ($depth)
            {
                    for ($depth; $depth > 0; $depth--) {
                            $this->arranged.= "\t";
                    }
                    return $this->arranged;
            }

            /**
             * @param array $comment
             * @param int $depth
             */
            private function format_comment ($comment, $depth)
            {

                    $this->arranged.= "\n";

                    $this->tabulate ($depth + 1);

                    $this->arranged.= "<div class='content'>";
                    if (Settings::get ('comment_markdown') and $comment->parsed):
                            $this->arranged.= $comment->parsed;
                    else:
                            $this->arranged.= '<p>' . nl2br ($comment->comment) . '</p>';
                    endif;
                    if(ci()->current_user->id != $comment->user_id):
                        $this->arranged.= '<div class="reply" id="reply_'.$comment->id.'"><a href="javascript:void(0);">Reply</a></div>';
                    endif; 
                    $this->arranged.= "</div>\n";
                    return $this->arranged;
            }

            /**
             * @param array $comment
;             * @param int $depth
             */
            private function print_parent ($comment, $depth = 0)
            {
                    $this->tabulate ($depth);
                    $this->arranged.= "<div class='comment' style='margin-left:" . ($depth * 20) . "px; border:1px dashed #efefef;padding:5px;'>";

                    foreach ($comment as $c) {
                            $this->arranged.= "<div class='image'>"
                                    . gravatar ($c->user_email, 60)
                                    . "</div>"
                                    . "<div class='details'>"
                                    . "<div class='name'>"
                                        . $c->user_name 
                                    . "</div>
                                            <div class='date'>
                                                <p>" . format_date ($c->created_on) . "</p>
                                        </div>";
                            $this->format_comment ($c, $depth);
                            $this->arranged.=  '</div>';
                            if (isset ($this->children[$c->id]))
                            {
                                    $this->print_parent ($this->children[$c->id], $depth + 1);
                            }
                    }

                    $this->tabulate ($depth);

                    $this->arranged.= "</div>\n";
                    return $this->arranged;
            }

            private function print_comments ()
            {
                    $return = '';
                    foreach ($this->parents as $c) {
                            $return.=$this->print_parent ($c);
                    }
                    return $return;
            }

    }
    