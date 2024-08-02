#include <iostream>
#include <vector>
#include <string>
#include <ctime>

// Estrutura para armazenar os detalhes do comentário
struct Comment {
    std::string name;
    std::string email;
    std::string message;
    std::string date;
};

// Função para obter a data atual no formato "DD/MM/YYYY"
std::string getCurrentDate() {
    time_t now = time(0);
    tm *ltm = localtime(&now);
    char buffer[11];
    strftime(buffer, 11, "%d/%m/%Y", ltm);
    return std::string(buffer);
}

// Função para coletar os detalhes do comentário do usuário
Comment collectComment() {
    Comment comment;
    std::cout << "Nome: ";
    std::getline(std::cin, comment.name);
    std::cout << "Email: ";
    std::getline(std::cin, comment.email);
    std::cout << "Comentário: ";
    std::getline(std::cin, comment.message);
    comment.date = getCurrentDate();
    return comment;
}

// Função para exibir os comentários
void displayComments(const std::vector<Comment>& comments) {
    for (const auto& comment : comments) {
        std::cout << "\n=======================================\n";
        std::cout << "Nome: " << comment.name << "\n";
        std::cout << "Email: " << comment.email << "\n";
        std::cout << "Comentário: " << comment.message << "\n";
        std::cout << "Data: " << comment.date << "\n";
        std::cout << "=======================================\n";
    }
}

int main() {
    std::vector<Comment> comments;
    char choice;
    
    do {
        std::cout << "Deseja adicionar um novo comentário? (s/n): ";
        std::cin >> choice;
        std::cin.ignore(); // Limpar o buffer de entrada
        
        if (choice == 's' || choice == 'S') {
            Comment newComment = collectComment();
            comments.push_back(newComment);
        }
        
        std::cout << "Deseja ver todos os comentários? (s/n): ";
        std::cin >> choice;
        std::cin.ignore();
        
        if (choice == 's' || choice == 'S') {
            displayComments(comments);
        }
        
    } while (choice == 's' || choice == 'S');
    
    return 0;
}
