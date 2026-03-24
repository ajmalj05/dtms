<?php

return [
    'max_history_points' => 5,
    'include_info_flags' => false,
    'include_psychiatric_note' => false,

    // Deterministic threshold rules for exact clinical trend flags.
    // Evaluation order is top-to-bottom as written here (critical -> high -> moderate -> info).
    'rules' => [
        'creatinine' => [
            'skip_if_stable' => true,
            'normal_min' => 0.5,
            'normal_max' => 1.2,
            'normal_variation_delta' => 0.2,
            'severities' => [
                'critical' => ['direction' => 'increase', 'min_to' => 5, 'min_delta' => 1],
                'high' => ['direction' => 'increase', 'min_to' => 2, 'min_delta' => 0.5],
                'moderate' => ['direction' => 'increase', 'min_delta' => 0.3],
                'info' => ['direction' => 'decrease', 'min_delta' => 0.3],
            ],
        ],
        'hba1c' => [
            'skip_if_stable' => true,
            'normal_max' => 6.5,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 9, 'min_delta' => 0.5],
                'moderate' => ['direction' => 'increase', 'min_delta' => 0.3],
                'info' => ['direction' => 'decrease', 'min_delta' => 0.3],
            ],
        ],
        'uacr' => [
            'skip_if_stable' => true,
            'normal_max' => 30,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 300, 'min_delta' => 30],
                'moderate' => ['direction' => 'increase', 'min_to' => 30, 'min_delta' => 10],
                'info' => ['direction' => 'decrease', 'min_delta' => 10],
            ],
        ],
        'egfr' => [
            'skip_if_stable' => true,
            'normal_min' => 90,
            'severities' => [
                'high' => ['direction' => 'decrease', 'max_to' => 30, 'min_delta' => 10],
                'moderate' => ['direction' => 'decrease', 'max_to' => 60, 'min_delta' => 5],
                'info' => ['direction' => 'increase', 'min_delta' => 5],
            ],
        ],
        'crp' => [
            'skip_if_stable' => true,
            'normal_max' => 3,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 10, 'min_delta' => 3],
                'moderate' => ['direction' => 'increase', 'min_to' => 3, 'min_delta' => 1],
                'info' => ['direction' => 'decrease', 'min_delta' => 1],
            ],
        ],
        'fbs' => [
            'skip_if_stable' => true,
            'normal_max' => 125,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 180, 'min_delta' => 30],
                'moderate' => ['direction' => 'increase', 'min_to' => 126, 'min_delta' => 20],
                'info' => ['direction' => 'decrease', 'min_delta' => 20],
            ],
        ],
        'ppbs' => [
            'skip_if_stable' => true,
            'normal_max' => 199,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 250, 'min_delta' => 40],
                'moderate' => ['direction' => 'increase', 'min_to' => 200, 'min_delta' => 25],
                'info' => ['direction' => 'decrease', 'min_delta' => 25],
            ],
        ],
        'plbs' => [
            'skip_if_stable' => true,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 250, 'min_delta' => 40],
                'moderate' => ['direction' => 'increase', 'min_to' => 180, 'min_delta' => 25],
                'info' => ['direction' => 'decrease', 'min_delta' => 25],
            ],
        ],
        'pre_lunch' => [
            'skip_if_stable' => true,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 250, 'min_delta' => 40],
                'moderate' => ['direction' => 'increase', 'min_to' => 180, 'min_delta' => 25],
                'info' => ['direction' => 'decrease', 'min_delta' => 25],
            ],
        ],
        'pre_dinner' => [
            'skip_if_stable' => true,
            'severities' => [
                'high' => ['direction' => 'increase', 'min_to' => 250, 'min_delta' => 40],
                'moderate' => ['direction' => 'increase', 'min_to' => 180, 'min_delta' => 25],
                'info' => ['direction' => 'decrease', 'min_delta' => 25],
            ],
        ],
        'pdbs' => [
            'skip_if_stable' => true,
            'severities' => [
                'moderate' => ['direction' => 'increase', 'min_to' => 180, 'min_delta' => 20],
                'info' => ['direction' => 'decrease', 'min_delta' => 20],
            ],
        ],
    ],
];
