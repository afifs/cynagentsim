<?php
class Agent {
    public $id, $domain, $strategy;

    public function __construct($id) {
        $this->id = $id;
        $this->domain = "confused";
        $this->strategy = "wait_and_watch";
    }

    public function perceive($volatility, $feedbackLag) {
        if ($volatility < 0.2 && $feedbackLag < 2)
            $this->domain = "obvious";
        elseif ($volatility < 0.4)
            $this->domain = "complicated";
        elseif ($volatility < 0.7)
            $this->domain = "complex";
        elseif ($volatility >= 0.7)
            $this->domain = "chaotic";
        else
            $this->domain = "confused";

        $this->decideAction();
    }

    private function decideAction() {
        $map = [
            "obvious"    => "apply_best_practice",
            "complicated"=> "analyze_then_act",
            "complex"    => "run_safe_experiment",
            "chaotic"    => "act_to_stabilize",
            "confused"   => "wait_and_watch"
        ];
        $this->strategy = $map[$this->domain] ?? "wait_and_watch";
    }

    public function toJSON() {
        return [
            "id" => $this->id,
            "domain" => $this->domain,
            "strategy" => $this->strategy
        ];
    }
}

// Generate agents and simulate
$agents = [];
$env = [
    "volatility" => round(mt_rand(0, 100) / 100, 2),
    "feedback_lag" => mt_rand(0, 5)
];

for ($i = 1; $i <= 5; $i++) {
    $agent = new Agent($i);
    $agent->perceive($env["volatility"], $env["feedback_lag"]);
    $agents[] = $agent->toJSON();
}

header('Content-Type: application/json');
echo json_encode([
    "environment" => $env,
    "agents" => $agents
], JSON_PRETTY_PRINT);
