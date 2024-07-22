<?php

namespace App\Jobs;

use App\Models\Person;
use App\Models\User;
use App\Models\WhatsappSend;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $comminments;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comminments, $user)
    {
        $this->comminments = $comminments;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $mensajes = [];
            foreach ($this->comminments as $comminment) {
                $concept = $comminment->conceptDebt ?? 'AVISO DE PAGO';
                $concept = strtoupper($concept);

                $student = Person::find($comminment->student_id);

                // $telephoneStudent = $student->telephone;

                $cadenaNombres = '';
                if ($student->typeofDocument == 'DNI') {
                    $cadenaNombres = $student->names . ' ' . $student->fatherSurname . ' ' . $student->motherSurname;
                } else if ($student->typeofDocument == 'RUC') {
                    $cadenaNombres = $student->businessName;
                }
                $studentParent = $student->representativeNames ?? 'Apoderado';
                $studentParentDni = $student->representativeDni ?? '';
                $telephoneStudent = '903017426'; //MOMENTANEO MI NUMERO TELEFÓNICO

                $mensajes[] =
                    [
                    "cellphone_number" => $telephoneStudent,
                    "title" => $concept,
                    "content" => [
                        "Estimado(a) {$studentParent} ({$studentParentDni}),",
                        "Le recordamos que la cuota de pensión escolar de su hijo(a) {$cadenaNombres} por un monto de {$comminment->paymentAmount} soles está pendiente de pago",
                        "Por favor, realice el pago a la brevedad posible para evitar recargos. Gracias por su cooperación. Atentamente, Colegio Manuel Pardo",
                    ],

                ];

                $user = $this->user;
                $person = Person::find($user->person_id);
                $cadenaNombres = '';
                if ($person->typeofDocument == 'DNI') {
                    $cadenaNombres = $person->names . ' ' . $person->fatherSurname . ' ' . $person->motherSurname;
                } else if ($person->typeofDocument == 'RUC') {
                    $cadenaNombres = $person->businessName;
                }

                $tipo = 'ENVI';
                $resultado = DB::select('SELECT COALESCE(MAX(CAST(SUBSTRING(number, LOCATE("-", number) + 1) AS SIGNED)), 0) + 1 AS siguienteNum FROM whatsapp_sends a WHERE SUBSTRING(number, 1, 4) = ?', [$tipo])[0]->siguienteNum;
                $siguienteNum = (int) $resultado;

                $data = [
                    'number' => $tipo . "-" . str_pad($siguienteNum, 8, '0', STR_PAD_LEFT),
                    'userResponsability' => $cadenaNombres,
                    'namesStudent' => $student->names . ' ' . $student->fatherSurname,
                    'dniStudent' => $student->documentNumber,
                    'namesParent' => $student->representativeDni . ' | ' . $student->representativeNames,
                    'infoStudent' => $student->level . ' ' . $student->grade . ' ' . $student->section,
                    'telephone' => $telephoneStudent,
                    'description' => $comminment->conceptDebt,
                    'conceptSend' => $comminment->conceptDebt,
                    'paymentAmount' => $comminment->paymentAmount,
                    'expirationDate' => $comminment->expirationDate,
                    'cuota' => $comminment->cuotaNumber,

                    'student_id' => $student->id,
                    'user_id' => $user->id,
                    'comminment_id' => $comminment->id,

                ];
                WhatsappSend::create($data);

            }

            $url = 'https://sistema.gesrest.net/api/send-massive-wa-messages';

            $response = Http::withHeaders([
                'Authorization' => '}*rA3>#pyM<dITk]]DFP2,/wc)1md_Y/',
            ])->post($url, [
                "messages" => $mensajes,
            ]);
            if ($response->successful()) {
                // Log success
                Log::info('WhatsApp message sent successfully to ' . $student->telephone);
            } else {
                // Log error
                Log::error('Failed to send WhatsApp message to ' . $student->phone . '. Status: ' . $response->status() . '. Response: ' . $response->body());
            }
            return response()->json(['message' => 'El mensaje de WhatsApp se ha enviado correctamente'], 200);
        } catch (Exception $e) {

            Log::error('Error to send Whatsapp, Student: ' . $comminment->telephoneStudent
                . 'Compromiso : id=>' . $comminment->id . ' | cuota=>' . $comminment->cuota .
                ' | conceptDebt=>' . $comminment->conceptDebt . ' | error=>'
                . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al enviar el mensaje de WhatsApp'], 500);
        }
    }
}
