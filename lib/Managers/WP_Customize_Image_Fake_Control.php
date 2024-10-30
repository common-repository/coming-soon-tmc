<?php
namespace cs_tmc\lib\Managers;



class WP_Customize_Image_Fake_Control extends \WP_Customize_Image_Control {

    public function to_json() {
        parent::to_json();

        $value = $this->value();
        if ( $value ) {
            // Get the attachment model for the existing file.
            $attachment_id = attachment_url_to_postid( $value );
            if ( $attachment_id ) {
                $this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
            } else {
                $this->json['attachment'] = array(
                    'id'                =>  1,
                    'title'             =>  '',
                    'filename'          =>  $value,
                    'url'               =>  $value,
                    'link'              =>  $value,
                    'alt'               =>  '',
                    'author'            =>  1,
                    'description'       =>  '',
                    'caption'           =>  '',
                    'name'              =>  $value,
                    'status'            =>  'inherit',
                    'uploadedTo'        =>  0,
                    'date'              =>  0,
                    'modified'          =>  0,
                    'menuOrder'         =>  0,
                    'mime'              =>  'image',    // image/png
                    'type'              =>  'image',
                    'subtype'           =>  'png',
                    'icon'              =>  '',
                    'dateFormatted'     =>  '',
                    'nonces'            => array(
                        'update'    =>  '',
                        'delete'    =>  '',
                        'edit'      =>  '',
                    ),
                    'editLink'              =>  '',
                    'meta'                  =>  '',
                    'authorName'            =>  'admin',
                    'filesizeInBytes'       =>  0,
                    'filesizeHumanReadable' => '',
                    'height'                =>  '',
                    'width'                 =>  '',
                    'orientation'           =>  'landscape',
                    'sizes'                 => array(
                        'thumbnail'             => array(
                            'height'                => 100,
                            'width'                 => 100,
                            'url'                   => $value,
                            'orientation'           => 'landscape'
                        ),
                        'full'                  => array(
                            'url'                   => $value,
                            'height'                => '',
                            'width'                 => '',
                            'orientation'           => 'landscape'
                        )
                    ),
                    'compat'                => array(
                        'item'      =>  '',
                        'meta'      =>  ''
                    )
                );
                
            }
        }
    }

}