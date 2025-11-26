<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Announcement;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class QuizWidget extends Component
{
    public $announcement;
    public $answers = [];
    public $score = 0;
    public $finished = false;
    public $hasAttempted = false;

    // Variables para el Supervisor
    public $showResults = false; // Para abrir/cerrar la lista
    public $attemptsList = [];   // Lista de trabajadores que dieron el examen

    public function mount(Announcement $announcement)
    {
        $this->announcement = $announcement;
        
        // Verificar si el usuario actual ya lo hizo
        $attempt = QuizAttempt::where('user_id', Auth::id())
                              ->where('announcement_id', $announcement->id)
                              ->first();
        if ($attempt) {
            $this->hasAttempted = true;
            $this->score = $attempt->score;
            $this->finished = true;
        }
    }

    // Lógica para que el trabajador responda
    public function submitQuiz()
    {
        $correctCount = 0;
        $totalQuestions = count($this->announcement->quiz_data);

        foreach ($this->announcement->quiz_data as $index => $q) {
            if (isset($this->answers[$index]) && $this->answers[$index] == $q['correct']) {
                $correctCount++;
            }
        }

        $this->score = round(($correctCount / $totalQuestions) * 100);
        $passed = $this->score >= 70;

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'announcement_id' => $this->announcement->id,
            'score' => $this->score,
            'passed' => $passed
        ]);

        $this->finished = true;
        $this->hasAttempted = true;
    }

    // Lógica para que el Supervisor vea resultados
    public function toggleResults()
    {
        $this->showResults = !$this->showResults;

        if ($this->showResults) {
            // Cargar la lista de intentos con el nombre del usuario
            $this->attemptsList = QuizAttempt::where('announcement_id', $this->announcement->id)
                                             ->with('user')
                                             ->latest()
                                             ->get();
        }
    }

    public function render()
    {
        return view('livewire.quiz-widget');
    }
}