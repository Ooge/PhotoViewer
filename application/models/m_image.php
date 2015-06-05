<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_image extends CI_Model {

    public function get_latest_images($limit) {
        $this->db->where('deleted', 0);
        $this->db->order_by('time', 'DESC');
        $query = $this->db->get('uploads', $limit);

        if($query->num_rows() > 0) {
            $results = $query->result_array();
            $images = array();
            foreach($results as $row) {
                $images[] = Image::get_by_gid($row['gid']);
            }
            return $images;
        }
        return 0;
    }

    public function thumbnailise() {
        $images = new Imagick(glob(FCPATH . 'uploads/*'));

        foreach($images as $image) {
            $image->thumbnailImage(219,219);
        }

        $images->writeImages();
    }
}
