import java.io.IOException;
import java.io.PrintWriter;
import java.net.InetAddress;

/**
 * Meter the connection time to the vocore.
 * 
 * This class is for measuring the connectiontime in a specific Wifi.
 * If the connection is lost, it wait 5 minutes for a reconnect.
 * 
 * @author Dominik Ernsberger
 *
 * 03.03.2016
 */
public class LogWlanTime
{
	static long startTimeDisconnect;
	static long durationDisconnect = 0;
	static PrintWriter writer;
	
	public static void main(String args[]) throws IOException{
		startTimeDisconnect = System.currentTimeMillis();
		writer = new PrintWriter("LogLifeTimeVocore.txt", "UTF-8");
		
		//wait 5 min for a connection.
		while(durationDisconnect < 300)
		{
			checkConnection();
			durationDisconnect = (System.currentTimeMillis() - startTimeDisconnect)/1000;
		}
		System.out.println("Program ended. Waited 5 min for a reconnect.");
		writer.println("Program ended. Waited 5 min for a reconnect.");
		writer.close();
	}
	
	/**
	 * Checks the connection to the vocore and meter the connectiontime.
	 * 
	 * This Method checks if the vocore is reachable and calculates the duration
	 * of the connection in the Wifi.
	 * @throws IOException
	 */
	private static void checkConnection() throws IOException
	{
		long startTimeConnection = 0;
		InetAddress address = InetAddress.getByName("192.168.61.1"); 
		
		//try to reach the vocore
		if(address.isReachable(2000))
		{
			if(startTimeConnection == 0){
				System.out.println("Connected!");
				writer.println("Connected!");
				startTimeConnection = System.currentTimeMillis();  
			}
			while(address.isReachable(2000)) 
			{ 

				try {
					Thread.sleep(2000);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			
			System.out.println("Disconnected!");
			writer.println("Disconnected!");
			//calculate the duration of the connection
			long currentTime = System.currentTimeMillis();
			long duration = (currentTime - startTimeConnection)/1000;
			long hours = 0;
			long minutes = 0;
			
			if(duration >= 3600)
			{
				hours = duration /3600;
				duration -= hours * 3600;
			}
			if(duration >= 60)
			{
				minutes = duration / 60;
				duration -= minutes * 60;
			}
	
			long seconds = duration;
			
			System.out.println("Connected Time: " + hours +":" + minutes+":"+seconds+"\n");
			writer.println("Connected Time: " + hours +":" + minutes+":"+seconds);
			writer.println(" ");
			
			
			startTimeDisconnect = currentTime;
		}
	}
	
}