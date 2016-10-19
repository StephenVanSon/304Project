package database;
import java.sql.*;


public class Main {
	public static void main(String args[])
	{
		try {
			
			DriverManager.registerDriver(new oracle.jdbc.driver.OracleDriver());
			//open sql connection through SSH tunnel
			Connection con = DriverManager.getConnection("jdbc:oracle:thin:@localhost:1522:ug", "ora_w5y9a", "a20030145");
			
			String insert = "";
			int userNum = 12845678;
			for(int i=0; i< 10; i++)
			{
				
			userNum += i;
			insert = "INSERT into users (studentNum, email, password, uname) VALUES (" + userNum +", 'testUser" + i + "@gmail.com', '1234', 'test user')";
			
			PreparedStatement stm = con.prepareStatement(insert);
			stm.execute();
			
			}
			
			Statement smt = con.createStatement();
			ResultSet rs = smt.executeQuery("SELECT * from users");
			//get everything from table AUTHORS
			
			
			while(rs.next())
			{
				System.out.println(rs.getString("email"));
			}
			
		
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}

}

