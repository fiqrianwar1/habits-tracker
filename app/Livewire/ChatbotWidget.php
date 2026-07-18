<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Auth;

class ChatbotWidget extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $userInput = '';
    public $isTyping = false;

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
        
        // Sapaan awal jika belum ada pesan sama sekali
        if ($this->isOpen && empty($this->messages)) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Halo bro! Ada yang bisa saya bantu terkait plan atau progres kamu hari ini?'
            ];
        }
    }

    public function sendMessage(ChatbotService $chatbotService)
    {
        $input = trim($this->userInput);
        if (empty($input)) return;

        // Tambahkan pesan user ke UI
        $this->messages[] = [
            'role' => 'user',
            'content' => $input
        ];
        
        $this->userInput = '';
        $this->isTyping = true; // Munculkan indikator loading (ngetik)

        // Kita biarkan UI update dulu
        $this->dispatch('chat-scrolled');
    }

    public function getBotResponse(ChatbotService $chatbotService)
    {
        if (!$this->isTyping) return;

        // Ambil riwayat chat terbaru untuk dikirim sebagai konteks (maks 10 pesan terakhir agar ringan)
        $history = array_slice($this->messages, -10);
        
        // Ambil input terakhir dari user
        $lastUserMsg = end($history);
        $messageToSend = $lastUserMsg['content'] ?? '';

        if (!empty($messageToSend)) {
            // Minta respon dari AI
            $response = $chatbotService->ask($messageToSend, Auth::user(), $history);
            
            // Tambahkan respon ke array
            $this->messages[] = [
                'role' => 'assistant',
                'content' => $response
            ];
        }

        $this->isTyping = false;
        $this->dispatch('chat-scrolled');
    }

    public function render()
    {
        return view('livewire.chatbot-widget');
    }
}
