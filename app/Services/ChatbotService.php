<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Activity;
use App\Models\CategoryTarget;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    public function ask(string $message, $user, array $history = [])
    {
        $context = $this->getUserContext($user);

        $systemPrompt = "Kamu adalah asisten AI yang ramah, asik (menggunakan sapaan 'bro' atau 'kak'), dan membantu pengguna (bernama {$user->name}) melacak kebiasaan (habits), rencana (plans), dan progres mereka. 
Gunakan data JSON berikut sebagai konteks aktivitas pengguna: 
" . json_encode($context) . "
Jawablah dengan ringkas, bahasa Indonesia yang santai, memotivasi, dan fokus pada data yang relevan dengan pertanyaan. Jangan berikan jawaban terlalu panjang, gunakan format markdown (bold, bullet points) agar mudah dibaca.";

        $messages = [];
        $messages[] = ['role' => 'system', 'content' => $systemPrompt];

        foreach ($history as $msg) {
            // Exclude error messages or loading states from history
            if (isset($msg['role']) && in_array($msg['role'], ['user', 'assistant'])) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }
        
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.api_key'),
                'Content-Type' => 'application/json',
            ])
            ->timeout(15)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile', // Model terbaru
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 600,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Maaf bro, saya tidak bisa mengerti responsnya.';
            }

            Log::error('Groq API Error', ['response' => $response->body()]);
            return 'Waduh bro, ada error waktu koneksi ke AI (Groq API). Coba cek koneksi atau API Key GROQ_API_KEY di .env nya ya. Kode error: ' . $response->status();
        } catch (\Exception $e) {
            Log::error('Chatbot Exception', ['error' => $e->getMessage()]);
            return 'Maaf bro, lagi ada kendala teknis nih. Coba lagi nanti ya!';
        }
    }

    private function getUserContext($user)
    {
        $now = Carbon::now();

        // 1. Get recent activities (last 7 days)
        $recentActivities = Activity::where('user_id', $user->id)
            ->where('date', '>=', $now->copy()->subDays(7)->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get(['date', 'category', 'duration_minutes', 'description']);

        // 2. Get this month's category targets
        $monthlyTargets = CategoryTarget::where('user_id', $user->id)
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->get(['category', 'target_days', 'minimum_hours_per_day']);

        // 3. Get pending tasks
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('is_completed', false)
            ->get(['title', 'date']);
            
        // 4. Get completed tasks today
        $completedTasksToday = Task::where('user_id', $user->id)
            ->where('is_completed', true)
            ->where('date', $now->format('Y-m-d'))
            ->get(['title']);

        return [
            'today_date' => $now->format('Y-m-d H:i'),
            'recent_activities_last_7_days' => $recentActivities,
            'monthly_targets' => $monthlyTargets,
            'pending_tasks' => $pendingTasks,
            'completed_tasks_today' => $completedTasksToday,
        ];
    }
}
