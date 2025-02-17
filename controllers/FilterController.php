<?php
require_once __DIR__ . '/../services/RecordService.php';

class FilterController
{
    private $recordService;

    public function __construct()
    {
        $this->recordService = new RecordService();
    }

    public function filterRecords($filters) {
        return $this->recordService->getFilteredRecords($filters);
    }
}
