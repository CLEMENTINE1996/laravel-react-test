<?php

namespace App\Services\Interfaces;

interface AnalyzeTicketInterface
{
    /**
     * Generate a summary and next action based on the issue description.
     * 
     * @param string $description
     * @return array ['summary' => string, 'suggested_next_actions' => string]
     */
    public function analyzeIssue(string $description): array;
}