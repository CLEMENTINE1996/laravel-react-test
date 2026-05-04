<?php

namespace App\Services;

use App\Services\Interfaces\AnalyzeTicketInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeTicketService implements AnalyzeTicketInterface
{
    /**
     * Analyze the issue description to generate a summary and suggest next actions.
     * Uses AI if available, otherwise falls back to rule-based logic.
     *
     * @param string $description - The issue description to analyze.
     * @return array - An array containing 'summary' and 'suggested_next_actions'.
     */
    public function analyzeIssue(string $description): array
    {
        if (config('services.groq.key')) {
            try {
                // get ai response based on the issue description
                return $this->getAiResponse($description);
            } catch (\Exception $e) {
                Log::error("Groq AI Service Failed: " . $e->getMessage());
                // if ai fails, use fallback summary and next action generation
                return $this->getFallback($description);
            }
        }
    }

    /**
     * Simulate an AI response for the given issue description.
     * In a real implementation, this would make an API call to an AI service.
     *
     * @param string $description
     * @return array
     */
    protected function getAiResponse(string $description): array
    {
        $response = Http::withToken(config('services.groq.key'))
            ->timeout(10) 
            ->post(config('services.groq.url'), [
                'model' => config('services.groq.model'),
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are a technical support assistant. Analyze the ticket and return ONLY a JSON object with "summary" (string) and "suggested_next_actions" (array of strings).'
                    ],
                    [
                        'role' => 'user', 
                        'content' => "Issue Description: $description"
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.2
            ]);

        if ($response->failed()) {
            throw new \Exception("Groq API Error: " . $response->body());
        }

        $content = json_decode($response->json('choices.0.message.content'), true);

        return [
            'summary' => $content['summary'] ?? 'Summary unavailable',
            'suggested_next_actions' => $content['suggested_next_actions'] ?? ['Assign to general queue']
        ];
    }

    protected function getFallback(string $description): array
    {
        $description = strtolower($description);
        $nextAction = 'Assign to general support team.';

        if (str_contains($description, 'database') || str_contains($description, 'server')) {
            $nextAction = 'Escalate to DevOps/Backend team immediately.';
        } 
        elseif (str_contains($description, 'login') || str_contains($description, 'password')) {
            $nextAction = 'Route to Authentication/Security module experts.';
        } 
        elseif (str_contains($description, 'ui') || str_contains($description, 'ux')) {
            $nextAction = 'Forward to Frontend/UI team for review.';
        }
        elseif (str_contains($description, 'payment') || str_contains($description, 'billing')) {
            $nextAction = 'Direct to Billing/Finance team for urgent handling.';
        }
        elseif (str_contains($description, 'crash') || str_contains($description, 'error')) {
            $nextAction = 'Prioritize for immediate investigation by the engineering team.';
        }
        elseif (str_contains($description, 'slow') || str_contains($description, 'performance')) {
            $nextAction = 'Flag for performance analysis by the technical team.';
        }
        elseif (str_contains($description, 'data loss') || str_contains($description, 'corruption')) {
            $nextAction = 'Escalate to data integrity specialists for urgent review.';
        }
        elseif (str_contains($description, 'security') || str_contains($description, 'vulnerability')) {
            $nextAction = 'Immediately route to the security team for assessment.';
        }

        return [
            'summary' => 'System: ' . ucfirst(substr($description, 0, 50)) . '...',
            'suggested_next_actions' => [$nextAction]
        ];
    }
}