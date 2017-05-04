<?php



class RestaurantListImportHandler implements \Maatwebsite\Excel\Files\ImportHandler
{

    public function handle(\App\RestaurantListImport $import)
    {
        // get the results
        $results = $import->get();
    }

}
?>