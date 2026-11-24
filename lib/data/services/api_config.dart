/// Configuration de l'API : adresse du serveur, délais et routes.
///
/// Sans configuration personnalisée, l'application utilise l'API de production.
///
/// Pour utiliser une API locale, passer son adresse au lancement :
/// `flutter run --dart-define=API_URL=http://10.0.2.2:8000/api`
abstract final class ApiConfig {
  static const String _urlProduction =
      'https://urbasign-production.up.railway.app/api';

  /// Adresse passée avec `--dart-define=API_URL=...` (vide si non fournie).
  static const String _urlPersonnalisee = String.fromEnvironment('API_URL');

  /// Adresse de base de l'API (sans "/" à la fin).
  static String get baseUrl {
    if (_urlPersonnalisee.isNotEmpty) return _urlPersonnalisee;
    return _urlProduction;
  }

  /// Temps max pour établir la connexion avec le serveur.
  static const Duration delaiConnexion = Duration(seconds: 10);

  /// Temps max pour recevoir la réponse complète.
  static const Duration delaiReception = Duration(seconds: 20);

  /// Temps max pour ENVOYER une photo (jusqu'à 1 Mo sur un réseau mobile
  /// parfois lent : on laisse plus de temps qu'une requête normale).
  static const Duration delaiEnvoi = Duration(seconds: 60);

  // ---------- Authentification ----------
  static const String connexion = '/auth/login';
  static const String profil = '/auth/me';
  static const String deconnexion = '/auth/logout';

  // ---------- Signalements ----------
  static const String signalements = '/signalements';
  static const String mesSignalements = '/signalements/mes-signalements';
  static String signalement(int id) => '/signalements/$id';
  static String statutSignalement(int id) => '/signalements/$id/statut';

  // ---------- Utilisateurs (admin) ----------
  static const String utilisateurs = '/users';
  static String utilisateur(int id) => '/users/$id';

  // ---------- Quartiers ----------
  static const String quartiers = '/quartiers';
}
