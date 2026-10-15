<?php

declare(strict_types=1);

namespace App\Web\Controllers;
use App\DTO\TeamDTO;
use App\DTO\TeamInputDTO;
use App\Models\Team;
use Core\Controller;
use Core\Request;
use Core\ValidationException;

final class TeamController extends Controller
{
    public function index(Request $request): void
    {
        $teams = Team::all();
        $this->view('teams/index.twig', ['teams' => $teams]);
    }

    public function create(Request $request): void
    {
        $this->view('teams/add.twig', [
            'team' => null,
            'action' => $this->view->url('/teams'),
        ]);
    }

    public function store(Request $request):void
    {
        try {
            $input = TeamInputDTO::fromArray($request->all());
        } catch (ValidationException $e) {
            $this->session->flash('errors', $e->errors);
            $this->session->flash('old', $request->all());
            $this->redirect('/teams/create');
        }
        
         Team::insert([
            'name' => $input->name
        ]);

        $this->session->flash('success', 'Equipe crée');
        $this->redirect('/teams');

    }
}